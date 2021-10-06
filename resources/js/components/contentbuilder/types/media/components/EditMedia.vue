<template>
    <div>
        <div v-if="files.length">
            <media-display 
                :file="files"
                :editing="editing"
                :adding="adding"
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
export default {
    props: {
        file: {
            type: Array,
            required: false
        },
        editing: {
            type: Boolean,
            required: false
        },
        adding: {
            type: Boolean,
            required: false
        },
        partId: {
            type: Number,
            required: false
        }
    },

    data () {
        return {
            files: []
        }
    },

    mounted () {
        this.files = this.file

        window.events.$on('media:remove', file => {
            if (this.files[0].file === file) {
                this.files = []
            }
        })

        window.events.$on('uploads:file', file => {
            if (!this.files.length && (this.editing || this.adding)) {
                this.files.push(file)
            }
        })
    }
}
</script>