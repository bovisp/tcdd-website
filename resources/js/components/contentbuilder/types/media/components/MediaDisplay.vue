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
                :src="file[0].file"
                type="video/mp4"
            >
        </video>

        <div 
            v-if="currentContentBuilder.new || data.editingPart"
            class="flex w-full mt-2"
        >
            <b-button
                type="is-text"
                size="is-small"
                class="has-text-danger ml-auto"
                @click.prevent="remove"
            >Delete file</b-button>
        </div>
    </div>
</template>

<script>
import fileExtension from '../../../../../helpers/fileExtension'
import contentBuilderData from '../../../../../mixins/contentBuilder'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        file: {
            type: Array,
            required: false
        },
        data: {
            type: Object,
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