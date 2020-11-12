<template>
    <div class="bg-light rounded-lg p-4 d-flex align-items-stretch mb-1">
        <div class="mr-3 position-relative">
            <radial-progress-bar 
                :diameter="70"
                :stroke-width="8"
                :inner-stroke-width="8"
                inner-stroke-color="#C7C7C7"
                :completed-steps="progress"
                :total-steps="totalSteps"
                start-color="#3490dc"
                stop-color="#3490dc">
            </radial-progress-bar>

            <div 
                class="position-absolute w-100 text-center"
                style="top: 24px; left: 0;"
            >
                <span 
                    class="font-weight-light text-primary"
                    style="font-size: .8rem;"
                >{{ progress }}%</span>
            </div>
        </div>

        <div class="d-flex flex-column justify-content-between">
            <div class="mb-2">
                <div
                    class="font-weight-bolder mr-3 text-secondary"
                    style="line-height: 1.25;"
                >
                    {{ upload.file.name }}
                </div>
                
                <div
                    class="text-muted"
                    style="line-height: 1.25;"
                >
                    <small>{{ sizeDisplay }} MB</small>
                </div>
            </div>

            <div class="text-muted align-baseline">
                <small>
                    <template v-if="state === states.WAITING">Waiting</template>

                    <template v-if="state === states.COMPLETE">Complete</template>

                    <template v-if="state === states.FAILED">Upload failed</template>

                    <template v-if="state === states.CANCELLED">Upload cancelled</template>

                    <template v-if="state === states.UNSUPPORTED">
                        <span class="text-danger">Sorry, this file type isn't supported</span>
                    </template>

                    <template v-if="state === states.UPLOADING">
                        <a 
                            href="#"
                            class="text-link"
                            @click.prevent="cancel"
                        >Cancel</a>
                    </template>
                </small>
            </div>
        </div>
    </div>
</template>

<script>
import states from '../states'
import RadialProgressBar from 'vue-radial-progress'

export default {
    components: {
        RadialProgressBar
    },

    props: {
        upload: {
            type: Object,
            required: true
        },
        baseURL: {
            type: String,
            required: true
        },
        endpoint: {
            required: true
        }
    },

    data () {
        return {
            state: null,
            progress: 0,
            states,
            axios: {
                cancel: null
            },
            totalSteps: 100
        }
    },

    computed: {
        sizeDisplay () {
            return (this.upload.file.size / 1000000).toFixed(2)
        }
    },

    watch: {
        'upload.queued' (queued) {
            if (this.state === states.UNSUPPORTED) {
                return
            }

            if (queued === false) {
                this.startUpload()
            }
        },

        progress (progress) {
            this.$emit('progress', {
                id: this.upload.id,
                progress
            })
        },

        state (state) {
            this.$emit('change', {
                id: this.upload.id,
                state
            })

            switch (state) {
                case states.CANCELLED:
                case states.FAILED:
                    this.progress = 0
                    break
            }
        }
    },

    methods: {
        cancel () {
            this.axios.cancel()
        },

        makeFormData (file) {
            const form = new FormData()
            form.append('file', file, file.name)

            return form
        },

        handleUploadProgress (e) {
            this.progress = Math.round((e.loaded * 100) / e.total)
        },

        async startUpload () {
            this.state = states.UPLOADING

            axios.post(
                `${this.baseURL}${this.endpoint}`, 
                this.makeFormData(this.upload.file),
                {
                    onUploadProgress: this.handleUploadProgress,
                    cancelToken: new axios.CancelToken(token => {
                        this.axios.cancel = token
                    })
                }
            ).then(({data}) => {
                window.events.$emit('uploads:file', data)

                this.state = states.COMPLETE
            }).catch(error => {
                if (error instanceof axios.Cancel) {
                    return this.state = states.CANCELLED
                }

                this.state = states.FAILED
            })
        }
    },

    mounted () {
        if (this.endpoint === null) {
            return this.state = states.UNSUPPORTED
        }

        this.state = states.WAITING
    }
}
</script>