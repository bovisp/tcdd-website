<template>
    <div>
        <div
            class="form-group"
        >
            <label 
                :class="{ 'text-danger': errors.title }"
                for="title"
            >
                Title (optional)
            </label>

            <input 
                type="text" 
                v-model="form.title"
                class="form-control"
                id="title"
                :class="{ 'is-invalid': errors.title }"
            >

            <p
                v-if="errors.title"
                v-text="errors.title[0]"
                class="invalid-feedback"
            ></p>
        </div>

        <div
            class="form-group"
        >
            <label 
                :class="{ 'text-danger': errors.caption }"
                for="caption"
            >
                Caption (optional)
            </label>

            <textarea 
                v-model="form.caption"
                class="form-control"
                id="caption"
                :class="{ 'is-invalid': errors.caption }"
            ></textarea>

            <p
                v-if="errors.caption"
                v-text="errors.caption[0]"
                class="invalid-feedback"
            ></p>
        </div>

        <uploader 
            :options="{
                baseURL: '',
                maxConcurrentUploads: 2,
                maxFiles: 50
            }"

            :handlers="{
                'image/gif': {
                    endpoint: '/uploads?type=image'
                },
                'image/jpeg': {
                    endpoint: '/uploads?type=image'
                },
                'image/png': {
                    endpoint: '/uploads?type=image'
                }
            }"
        />

        <div 
            class="mt-n3 mb-4 text-danger small"
            v-if="errors.files"
            v-text="errors.files[0]"
        ></div>

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
                content_builder_type_id: 2,
                files: [],
                title: '',
                caption: '',
                is_tab_section: this.isTabSectionPart
            },
            errors: []
        }
    },

    methods: {
        store () {
            axios.post(`/series/${this.seriesId}/animation`, this.form)
                .then(({data}) => {
                    if (this.isTabSectionPart === false) {
                        window.events.$emit('part:created', data)

                        this.reset()
                    } else {
                        this.$emit('tab-part-section-content:created', data)
                    }
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        async cancel () {
            await axios.delete('/uploads', {
                data: {
                    files: this.form.files
                }
            })

            this.reset()
        },

        reset () {
            this.form.files = []
            this.form.title = ''
            this.form.caption = ''

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
        
        window.events.$on('uploads:file', file => {
            this.form.files.push({
                file: file['file'],
                original: file['original']
            })
        })
    }
}
</script>