<template>
    <div>
        <img 
            v-if="fileExtension(file[0].file) === 'image'"
            :src="`${urlBase}${file[0].file}`" 
            class="block mx-auto max-w-full h-auto"
        >

        <video 
            controls
            v-if="fileExtension(file[0].file) === 'video'"
            class="block mx-auto max-w-full h-auto"
        >
            <source 
                :src="`${urlBase}${file[0].file}`"
                type="video/mp4"
            >
        </video>
    </div>
</template>

<script>
import fileExtension from '../../../../../helpers/fileExtension'

export default {
    props: {
        file: {
            type: Array,
            required: false
        }
    },

    methods: {
        fileExtension,

        remove () {
            window.events.$emit('media:remove', this.file[0].file)
        }
    }        
}
</script>