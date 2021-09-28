<template>
    <div class="mb-4">
        <vue-editor 
            v-model="content"
        ></vue-editor>

        <div 
            class="mt-1 text-red-500 text-xs"
            v-if="errors.content"
            v-text="errors.content[0]"
        ></div>
    </div>
</template>

<script>
import { VueEditor, Quill } from 'vue2-editor'
import { isEmpty } from 'lodash-es'

export default {
    components: {
        VueEditor
    },

    props: {
        data: {
            type: Object,
            required: false
        }
    },

    data () {
        return {
            content: ''
        }
    },
    
    watch: {
        content () {
            window.events.$emit('content:update-form', this.content)
        }
    },

    mounted () {
        if (!isEmpty(this.data)) {
            this.content = this.data.data.content
                .replace(/<p><br><\/p>/g, '')
                .replace(/<p class="ql-align-justify"><br><\/p>/g, '')
                .replace(/<p class="ql-align-right"><br><\/p>/g, '')
                .replace(/<p class="ql-align-left"><br><\/p>/g, '')
        }
    }
}
</script>