<template>
    <div
        v-if="!isEmpty(data)"
        class="content my-4"
        v-html="content"
    ></div>
</template>

<script>
import contentBuilderData from '../../../../mixins/contentBuilder'
import { isEmpty } from 'lodash-es'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        data: {
            type: Object,
            required: true
        }
    },

    computed: {
        content () {
            return this.data.data.content
                .replace(/<p><br><\/p>/g, '')
                .replace(/<p class="ql-align-justify"><br><\/p>/g, '')
                .replace(/<p class="ql-align-right"><br><\/p>/g, '')
                .replace(/<p class="ql-align-left"><br><\/p>/g, '')
        },
    },

    methods: {
        isEmpty
    }
}
</script>