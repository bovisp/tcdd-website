<template>
    <div>
        <div v-if="files.length">
            <media-display 
                :file="files"
                :data="data"
                :id="currentContentBuilder.id"
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
        }
    },

    data () {
        return {
            files: [],
        }
    },

    watch: {
        files: {
            deep: true,

            handler () {
                if (isEmpty(this.data)) {
                    this.updateNewForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partial: true,
                        payload: {
                            filename: this.files
                        }
                    })
                } else {
                    this.updateEditForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partDataId: this.data.data.id,
                        type: this.data.builderType.type,
                        partial: true,
                        payload: {
                            filename: this.files
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
            this.files = this.data.data.filename
        }

        window.events.$on('media:remove', async file => {
            if (typeof this.files !== 'undefined') {
                if (this.files[0].file === file) {
                    await this.removeFile(this.files)
                    
                    this.files = []
                }
            }
        })

        window.events.$on('uploads:file', file => {
            if (this.data) {
                if (!this.files.length && this.data.editingPart) {
                    this.files.push(file)

                    return
                }
            }

            if (!this.files.length && this.currentContentBuilder.new) {
                    this.files.push(file)
            }
        })
    }
}
</script>