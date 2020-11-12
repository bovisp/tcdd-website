<template>
    <div>
        <form>
            <div class="form-group">
                <vue-editor 
                    v-model="form.content"
                ></vue-editor>

                <div 
                    class="mt-1 text-danger small"
                    v-if="errors.content"
                    v-text="errors.content[0]"
                ></div>
            </div>

            <div class="form-group mb-0 d-flex">
                <button 
                    class="btn"
                    :class="createButtonClasses"
                    @click.prevent="store"
                >
                    {{ createButtonText }}
                </button>
                
                <button 
                    class="btn btn-text ml-auto"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        seriesId: {
            type: Number,
            required: true
        },
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
            default: 'btn-primary'
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
        }
    },

    data () {
        return {
            form: {
                content: '',
                content_builder_type_id: 1,
                is_tab_section: this.isTabSectionPart
            },
            errors: {}
        }
    },

    methods: {
        store () {
            axios.post(`/series/${this.seriesId}/content`, this.form)
                .then(({data}) => {
                    if (!this.isTabSectionPart) {
                        window.events.$emit('part:created', data)

                        this.cancel()
                    } else {
                        this.$emit('tab-part-section-content:created', data)
                    }
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        cancel () {
            this.form.content = ''

            window.events.$emit('add-part:cancel')
        }
    },

    mounted () {
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