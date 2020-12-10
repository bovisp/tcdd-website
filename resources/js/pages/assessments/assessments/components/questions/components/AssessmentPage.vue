<template>
    <div>
        <hr class="border my-6">

        <h2 class="text-2xl font-light">
            Page {{ page.number }}
        </h2>

        <assessment-questions-content-picker
            @content:type="setType"
            v-if="!type"
        />

        <assessment-questions-content-add
            v-if="type"
            :type="type"
            @content-add:cancel="type = ''"
            :page="page"
        />
    </div>
</template>

<script>
export default {
    props: {
        page: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            type: ''
        }
    },

    methods: {
        setType (type) {
            this.type = type
        }
    }
}
</script>