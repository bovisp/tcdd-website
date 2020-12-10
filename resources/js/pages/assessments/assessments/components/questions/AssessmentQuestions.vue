<template>
    <div>
        <assessment-pages />
        <!-- <assessment-questions-content-picker 
            @content:type="changeType"
            v-if="!type"
        />

        <assessment-questions-content-add
            v-if="type"
            :type="type"
            @content-add:cancel="type = ''"
        /> -->
    </div>
</template>

<script>
export default {
    // data () {
    //     return {
    //         type: ''
    //     }
    // },

    // methods: {
    //     changeType (type) {
    //         this.type = type
    //     }
    // }
}
</script>