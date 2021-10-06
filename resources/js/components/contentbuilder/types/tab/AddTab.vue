<template>
    <div>
        <form>
            <edit-tabs 
                :lang="lang"
            />

            <div class="level mb-0 mt-2">
                <div class="level-left">
                    <div class="level-item">
                        <b-button
                            type="is-info"
                            size="is-small"
                            icon-left="pencil"
                            @click.prevent="store"
                        >Create tab</b-button>
                    </div>
                </div>

                <div class="level-right">
                    <div class="level-item">
                        <b-button
                            type="is-text"
                            size="is-small"
                            @click.prevent="cancelAddingPart"
                        >Cancel adding tab</b-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { isEmpty, forIn } from 'lodash-es'
import { mapGetters } from 'vuex'

export default {
    props: {
        editStatus: {
            type: Boolean,
            required: true
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        },
        lang: {
            type: String,
            required: true,
        }
    },

    data () {
        return {
            form: null,
            builderId: null
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'contentIds'
        })
    },

    watch: {
        errors: {
            deep: true,

            handler () {
                forIn(this.errors, (value, key) => {
                    if (key.includes('tabSections')) {
                        this.$buefy.dialog.alert({
                            title: 'Error',
                            message: value[0],
                            type: 'is-danger',
                            ariaRole: 'alertdialog',
                            ariaModal: true
                        })
                    }
                })
            }
        }
    },

    methods: {
        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/content-builder/${this.builderId}/tab`, {
                content_builder_type_id: this.form.content_builder_type_id,
                title: this.form.title,
                caption: this.form.caption,
                tabSections: this.form.tabs
            })

            window.events.$emit('part:created', {
                data,
                contentBuilderId: this.builderId
            })
        },

        async cancelAddingPart () {
            for await (const tab of this.form.tabs) {
                if (!isEmpty(tab.data)) {
                    await axios.delete(`${this.urlBase}/api/parts/tabs/cancel`, {
                        data: { tab }
                    })
                }
            }

            window.events.$emit('add-part:cancel', this.builderId)
        }
    },

    mounted () {
        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]

        // window.events.$on('tabs:update-tab-list', tabs => this.form.tabs = tabs)

        // window.events.$on('tabs:update-form', form => this.form = form)
    }
}
</script>