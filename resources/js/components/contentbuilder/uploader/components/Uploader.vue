<template>
    <div>
        <uploader-form 
            @chosen="handleFilesChosen"
            v-show="oneUploadedFileOnly"
            :options="options"
        />

        <div class="mb-4 flex justify-between px-4 text-gray-700 text-sm">
            <span>{{ this.uploads.length }} uploads ({{ currentUploadCount }} in progress) / {{ completedUploadCount }} complete</span>
            <span>{{ overallProgress }}% complete</span>
        </div>

        <uploader-file
            :endpoint="determineEndpointFor(upload.file.type)"
            :baseURL="options.baseURL"
            v-for="(upload, index) in uploads"
            :key="index"
            :upload="upload"
            @progress="handleUploadProgress"
            @change="handleUploadChange"
        />
    </div>
</template>

<script>
import options from '../options'
import states from '../states'
import { get, map, reduce, filter } from 'lodash-es'
import uuid from 'uuid/v4'

export default {
    props: {
        options: {
            required: false,
            type: Object,
            default: () => options
        },
        handlers: {
            required: true,
            type: Object
        }
    },

    data () {
        return {
            uploads: []
        }
    },

    computed: {
        oneUploadedFileOnly () {
            if (this.options.maxFiles !== 1) {
                return true
            }

            return this.completedUploadCount !== this.options.maxFiles
        },

        currentUploadCount () {
            return filter(this.uploads, upload => upload.uploading).length
        },

        completedUploadCount () {
            return filter(this.uploads, upload => upload.complete).length
        },

        overallProgress () {
            if (this.uploads.length === 0) {
                return 0
            }

            let uploads = filter(this.uploads, upload => !upload.cancelled && !upload.failed)

            if (uploads.length === 0) {
                return 0
            }

            let progress = parseInt(
                reduce(uploads, (a, b) => a + b.progress, 0) / uploads.length
            )

            if (progress === 100) {
                console.log('here')
                window.events.$emit('uploader:complete')
            }

            return progress
        }
    },

    methods: {
        handleUploadChange (e) {
            switch (e.state) {
                case states.UPLOADING:
                    this.uploads = map(this.uploads, upload => {
                        if (e.id === upload.id) {
                            upload.uploading = true
                        }

                        return upload
                    })
                    break
                case states.COMPLETE:
                    this.uploads = map(this.uploads, upload => {
                        if (e.id === upload.id) {
                            upload.complete = true
                            upload.uploading = false
                        }

                        return upload
                    })
                    break
                case states.CANCELLED:
                    this.uploads = map(this.uploads, upload => {
                        if (e.id === upload.id) {
                            upload.cancelled = true
                            upload.uploading = false
                        }

                        return upload
                    })
                    break
                case states.FAILED:
                    this.uploads = map(this.uploads, upload => {
                        if (e.id === upload.id) {
                            upload.failed = true
                            upload.uploading = false
                        }

                        return upload
                    })
                    break
            }
        },

        handleUploadProgress (e) {
            this.uploads = map(this.uploads, upload => {
                if (e.id === upload.id) {
                    upload.progress = e.progress
                }

                return upload
            })
        },

        determineEndpointFor (fileType) {
            return get(this.handlers[fileType], 'endpoint', null)
        },

        handleFilesChosen (files) {
            this.uploads.push(...Array.from(files).map(file => {
                return {
                    id: uuid(),
                    progress: 0,
                    uploading: false,
                    complete: false,
                    cancelled: false,
                    failed: false,
                    queued: true,
                    file
                }
            }))
        }
    },

    mounted () {
        if (this.options.maxConcurrentUploads > this.options.maxFiles) {
            this.options.maxConcurrentUploads = this.options.maxFiles
        }

        setInterval(() => {
            if (this.currentUploadCount >= this.options.maxConcurrentUploads) {
                return
            }

            let queued = filter(this.uploads, upload => upload.queued === true)

            if (queued.length) {
                if (this.completedUploadCount >= this.options.maxFiles) {
                    return
                }

                queued[0].queued = false
            }
        }, 500);
    }
}
</script>