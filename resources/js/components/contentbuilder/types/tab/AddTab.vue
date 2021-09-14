<template>
    <div>
        <form>
           <b-field>
                <b-input 
                    placeholder="Add an optional tab title..."
                    size="is-medium"
                    class="borderless-input borderless-input-md"
                    v-model="form.title"
                ></b-input>
            </b-field>

            <tab-list 
                :tabs="form.tabs"
                :lang="lang"
                @tabs:update-tab-count="updateTabs"
            />

            <b-field>
                <b-input 
                    placeholder="Add an optional tab caption..."
                    class="borderless-input"
                    v-model="form.caption"
                ></b-input>
            </b-field>

            <div class="level mb-0">
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
import { find, isEmpty } from 'lodash-es'
import { mapGetters } from 'vuex'

export default {
    props: {
        editStatus: {
            type: Boolean,
            required: true
        },
        lang: {
            type: String,
            required: true,
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        }
    },

    data () {
        return {
            form: {
                content_builder_type_id: 5,
                title: '',
                caption: '',
                tabs: []
            },
            builderId: null
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'contentIds'
        })
    },

    watch: {
        isAddPartActive () {
            window.events.$emit('tabs:add-part-modal')
        }
    },

    methods: {
        isEmpty,

        updateTab (payload) {
            let tab = find(this.form.tabs, tab => tab.id === payload.tabToEdit.id)

            tab.label = payload.tabToEdit.label

            tab.order = payload.tabToEdit.order

            if (parseInt(payload.tabToEdit.order) !== parseInt(payload.originalOrder)) {
                let tab = find(this.form.tabs, tab => tab.order === payload.tabToEdit.order && tab.id !== payload.tabToEdit.id)

                tab.order = payload.originalOrder
            }

            window.events.$emit('tabs:update-tab-list', this.form.tabs)
        },

        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/content-builder/${this.builderId}/tab`, {
                content_builder_type_id: this.form.content_builder_type_id,
                title: this.form.title,
                caption: this.form.caption,
                tabSections: this.tabs
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
        },

        updateTabs (tabs) {
            this.form.tabs = tabs
        }
    },

    mounted () {
        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]

        window.events.$on('tabs:update-edited-tab', tab => this.updateTab(tab))

        window.events.$on('tabs:update-tab-list', tabs => this.form.tabs = tabs)
    }
}
</script>