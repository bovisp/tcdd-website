<template>
    <div class="my-4">
        <p 
            class="mb-4 text-center font-light w-full text-xl"
            v-if="typeof part.data !== 'undefined' && part.data.title"
        >
            {{ part.data.title }}
        </p>

        <div v-if="part.data">
            <media-display 
                :file="part.data.filename"
            />
        </div>

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