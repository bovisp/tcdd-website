<template>
    <div>
        <assessment-instructors-index 
            v-if="showIndex"
            @create="showIndex = false"
        />

        <assessment-instructors-create 
            v-else
            @cancel="reload"
        />
    </div>
</template>

<script>
export default {
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