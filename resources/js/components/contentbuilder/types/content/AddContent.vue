<template>
    <div>
        <form>
            <div class="mb-4">
                <vue-editor 
                    v-model="form.content"
                ></vue-editor>

                <div 
                    class="mt-1 text-red-500 text-xs"
                    v-if="errors.content"
                    v-text="errors.content[0]"
                ></div>
            </div>

            <div class="flex">
                <button 
                    class="button is-small is-info"
                    :class="createButtonClasses"
                    @click.prevent="store"
                >
                    {{ createButtonText }}
                </button>
                
                <button 
                    class="button is-text is-small ml-auto"
                    @click.prevent="cancel"
                >
                    {{ trans('generic.cancel') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { VueEditor, Quill } from 'vue2-editor'

export default {
    components: {
        VueEditor
    },

    props: {
        editStatus: {
            type: Boolean,
            required: true
        },
        createButtonText: {
            type: String,
            required: false,
            default: 'Create'
        },
        createButtonClasses: {
            type: String,
            required: false,
            default: 'btn-blue'
        },
        isTabSectionPart: {
            type: Boolean,
            required: false,
            default: false
        },
        partId: {
            type: Number,
            required: false,
            default: null
        },
        sectionTitle: {
            type: String,
            required: false,
            default: null
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
                content: '',
                content_builder_type_id: 2,
                is_tab_section: this.isTabSectionPart
            },
            builderId: null
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'contentIds'
        })
    },

    methods: {
        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/content-builder/${this.builderId}/content`, this.form)

            if (!this.isTabSectionPart) {
                window.events.$emit('part:created', {
                    data,
                    contentBuilderId: this.builderId
                })

                this.cancel()
            } else {
                window.events.$emit('tab-content:created', data)
            }
        },

        cancel (partId) {
            this.form.content = ''

            if (this.isTabSectionPart) {
                window.events.$emit('tab-content:cancel-add')

                return
            }

            window.events.$emit('add-part:cancel', this.builderId)
        }
    },

    mounted () {
        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]

        if (this.partId) {
            this.form.part_id = this.partId
        }

        if (this.sectionTitle && this.partId) {
            this.form.tab_part_section_title = this.sectionTitle
        }

        window.events.$on('tab-content:section', section => {
            this.form.tab_part_section_title = section.title
        })
    }
}
</script>