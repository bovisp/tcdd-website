<template>
    <div>
        <assessment-instructors-index 
            v-if="showIndex && visible"
            @create="showIndex = false"
        />

        <assessment-instructors-create 
            v-if="!showIndex && visible"
            @cancel="reload"
        />
    </div>
</template>

<script>
export default {
    props: {
        visible: {
            type: Boolean,
            required: true
        }
    },

    data () {
        return {
            showIndex: true
        }
    },

    methods: {
        reload () {
            this.showIndex = true

            window.events.$emit('assessments:reload')
        },
    }
}
</script>