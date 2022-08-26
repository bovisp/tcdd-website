<template>
    <div>
        <b-field>
            <b-input 
                :placeholder="trans('js_components_contentbuilder_generic.addoptionaltitle')"
                size="is-medium"
                class="borderless-input borderless-input-md"
                v-model="form.title"
            ></b-input>
        </b-field>

        <div v-if="form.files.length">
            <media-display 
                :file="form.files"
            />
        </div>

        <div 
            class="card"
            v-else
        >
            <div class="card-content">
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
            </div>
        </div>

        <b-field class="mt-2">
            <b-input 
                :placeholder="trans('js_components_contentbuilder_generic.addoptionalcaption')"
                class="borderless-input"
                v-model="form.caption"
            ></b-input>
        </b-field>
    </div>
</template>

<script>
import contentBuilderData from '../../../../../mixins/contentBuilder'
import { isEmpty } from 'lodash-es'
import { mapActions } from 'vuex'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        data: {
            type: Object,
            required: false
        },
        isTabSectionPart: {
            type: Boolean,
            required: false,
            default: false
        },
        tabPartDataId: {
            type: Number,
            required: false,
            default: null
        }
    },

    data () {
        return {
            form: {
                files: [],
                title: '',
                caption: ''
            }
        }
    },

    watch: {
        form: {
            deep: true,

            handler () {
                if (isEmpty(this.data)) {
                    this.updateNewForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partial: true,
                        tabPartDataId: this.tabPartDataId,
                        isTabSectionPart: this.isTabSectionPart,
                        payload: {
                            filename: this.form.files,
                            title: this.form.title,
                            caption: this.form.caption
                        }
                    })
                } else {
                    this.updateEditForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partDataId: this.data.data.id,
                        tabPartDataId: this.tabPartDataId,
                        type: this.data.builderType.type,
                        payload: {
                            filename: this.form.files,
                            title: this.form.title,
                            caption: this.form.caption
                        }
                    })
                }
            }
        },
    },

    methods: {
        ...mapActions({
            updateNewForm: 'contentbuilder/updateNewForm',
            updateEditForm: 'contentbuilder/updateEditForm',
            removeFile: 'contentbuilder/removeFile'
        })
    },

    mounted () {
        if (!isEmpty(this.data)) {
            this.form.files = this.data.data.filename

            this.form.title = this.data.data.title

            this.form.caption = this.data.data.caption
        }

        window.events.$on('media:remove', async file => {
            if (typeof this.form.files !== 'undefined') {
                if (this.form.files[0].file === file) {
                    await this.removeFile(this.form.files)
                    
                    this.form.files = []
                }
            }
        })

        window.events.$on('uploads:file', file => {
            if (this.data) {
                if (!this.form.files.length && this.data.editingPart) {
                    this.form.files.push(file)

                    return
                }
            }

            if (!this.form.files.length && this.currentContentBuilder.new) {
                    this.form.files.push(file)

                    return
            }

            if (!this.form.files.length && this.isTabSectionPart) {
                this.form.files.push(file)

                return
            }
        })
    }
}
</script>