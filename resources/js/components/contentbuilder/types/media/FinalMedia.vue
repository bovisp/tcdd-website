<template>
    <div class="my-4">
        <p 
            class="mb-4 text-center font-light w-full text-xl"
            v-if="typeof part.data !== 'undefined' && part.data.title"
        >
            {{ part.data.title }}
        </p>

        <template  v-if="typeof part.data !== 'undefined'">
            <template v-if="fileExtension(part.data.filename[0].file) === 'image'">
                <img 
                    :src="`${urlBase}${part.data.filename[0].file}`" 
                    :alt="part.data.caption"
                    class="block mx-auto max-w-full h-auto"
                >
            </template>

            <template v-if="fileExtension(part.data.filename[0].file) === 'video'">
                <video 
                    controls
                    class="block mx-auto max-w-full h-auto"
                >
                    <source 
                        :src="part.data.filename[0].file"
                        type="video/mp4"
                    >
                </video>
            </template>
        </template>

        <p 
            class="mb-0 mt-2 text-gray-700 w-3/4 mx-auto"
            v-if="typeof part.data !== 'undefined' && part.data.caption"
        >
            <small>{{ part.data.caption }}</small>
        </p>
    </div>
</template>

<script>
import fileExtension from '../../../../helpers/fileExtension'

export default {
    props: {
        part: {
            type: Object,
            required: true
        }
    },

    methods: {
        fileExtension
    }
}
</script>