<template>
    <div>
        <div v-if="file.length">
            <media-display 
                :file="file"
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
        }
    }
}
</script>