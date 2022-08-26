<template>
    <div class="bg-gray-100 rounded p-6 flex align-stretch mb-1">
        <div class="mr-4 relative">
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
                class="absolute w-full text-center"
                style="top: 24px; left: 0;"
            >
                <span 
                    class="font-light text-blue-700"
                    style="font-size: .8rem;"
                >{{ progress }}%</span>
            </div>
        </div>

        <div class="flex flex-col justify-between">
            <div class="mb-2">
                <div
                    class="font-medium mr-4 text-gray-800"
                    style="line-height: 1.25;"
                >
                    {{ upload.file.name }}
                </div>
                
                <div
                    class="text-gray-700"
                    style="line-height: 1.25;"
                >
                    <small>{{ sizeDisplay }} MB</small>
                </div>
            </div>

            <div class="text-gray-700 align-baseline">
                <small>
                    <template v-if="state === states.WAITING">{{ trans('js_components_uploader.waiting') }}</template>

                    <template v-if="state === states.COMPLETE">{{ trans('js_components_uploader.complete') }}</template>

                    <template v-if="state === states.FAILED">{{ trans('js_components_uploader.uploadfailed') }}</template>

                    <template v-if="state === states.CANCELLED">{{ trans('js_components_uploader.uploadcancelled') }}</template>

                    <template v-if="state === states.UNSUPPORTED">
                        <span class="text-red-500">{{ trans('js_components_uploader.notsupported') }}</span>
                    </template>

                    <template v-if="state === states.UPLOADING">
                        <a 
                            href="#"
                            class="text-blue-400"
                            @click.prevent="cancel"
                        >{{ trans('generic.cancel') }}</a>
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
                `${this.urlBase}${this.endpoint}`, 
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