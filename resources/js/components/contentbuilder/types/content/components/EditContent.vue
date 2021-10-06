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
import contentBuilderData from '../../../../../mixins/contentBuilder'
import { mapActions } from 'vuex'

export default {
    mixins: [
        contentBuilderData
    ],

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
            if (isEmpty(this.data)) {
                this.updateNewForm({
                    currentContentBuilder: this.currentContentBuilder,
                    payload: {
                        content: this.content
                    }
                })
            } else {
                this.updateEditForm({
                    currentContentBuilder: this.currentContentBuilder,
                    partDataId: this.data.data.id,
                    type: this.data.builderType.type,
                    payload: {
                        content: this.content
                    }
                })
            }
        }
    },

    methods: {
        ...mapActions({
            updateNewForm: 'contentbuilder/updateNewForm',
            updateEditForm: 'contentbuilder/updateEditForm'
        })
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