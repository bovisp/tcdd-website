<template>
    <div
        v-if="typeof part.data !== 'undefined'"
        class="content my-4"
        v-html="content"
    ></div>
</template>

<script>
export default {
    props: {
        part: {
            type: Object,
            required: true
        }
    },

    computed: {
        content () {
            return this.part.data.content
                .replace(/<p><br><\/p>/g, '')
                .replace(/<p class="ql-align-justify"><br><\/p>/g, '')
                .replace(/<p class="ql-align-right"><br><\/p>/g, '')
                .replace(/<p class="ql-align-left"><br><\/p>/g, '')
        },
    },
}
</script>