<template>
    <div>
        <div
            class="mb-4"
        >
            <label
                class="block text-gray-700 font-bold mb-2" 
                :class="{ 'text-red-500': errors.title }"
                for="title"
            >
                {{ trans('js_components_contentbuilder_types_media_addmedia.title') }}
            </label>

            <input 
                type="text" 
                v-model="form.title"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                id="title"
                :class="{ 'border-red-500': errors.title }"
            >

            <p
                v-if="errors.title"
                v-text="errors.title[0]"
                class="text-red-500 text-xs"
            ></p>
        </div>

        <div
            class="mb-4"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
                :class="{ 'text-red-500': errors.caption }"
                for="caption"
            >
                {{ trans('js_components_contentbuilder_types_media_addmedia.caption') }}
            </label>

            <textarea 
                v-model="form.caption"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                id="caption"
                :class="{ 'border-red-500': errors.caption }"
            ></textarea>

            <p
                v-if="errors.caption"
                v-text="errors.caption[0]"
                class="text-red-500 text-xs"
            ></p>
        </div>

        <uploader 
            :options="{
                baseURL: '',
                maxConcurrentUploads: 1,
                maxFiles: 1
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
                },
                'video/mp4': {
                    endpoint: '/uploads?type=video'
                }
            }"
        />

        <div 
            class="-mt-3 mb-6 text-red-500 text-xs"
            v-if="errors.filename"
            v-text="errors.filename[0]"
        ></div>

        <div class="flex">
            <button 
                class="btn btn-sm text-sm"
                :class="createButtonClasses"
                @click.prevent="store"
            >
                {{ createButtonText }}
            </button>
            
            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="cancel"
            >
                {{ trans('js_components_contentbuilder_types_media_addmedia.cancel') }}
            </button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        editStatus: {
            type: Boolean,
            required: true
        },
        createButtonText: {
            type: String,
            required: false,
            default: () => this.trans('js_components_contentbuilder_types_media_addmedia.create')
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
                content_builder_type_id: 4,
                filename: [],
                title: '',
                caption: '',
                is_tab_section: this.isTabSectionPart
            },
            builderId: null
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'questions/contentIds'
        })
    },

    methods: {
        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/content-builder/${this.builderId}/media`, this.form)

            if (!this.isTabSectionPart) {
                window.events.$emit('part:created', {
                    data,
                    contentBuilderId: this.builderId
                })

                this.reset()
            } else {
                this.$emit('tab-part-section-content:created', data)
            }
        },

        async cancel () {
            await axios.delete(`${this.urlBase}/uploads`, {
                data: {
                    files: this.form.filename
                }
            })

            this.reset()
        },

        reset () {
            this.form.filename = []
            this.form.title = ''
            this.form.caption = ''

            if (this.isTabSectionPart) {
                this.$emit('tab-content:cancel-add')

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
        
        window.events.$on('uploads:file', file => {
            this.form.filename.push({
                file: file['file'],
                original: file['original']
            })
        })
    }
}
</script>