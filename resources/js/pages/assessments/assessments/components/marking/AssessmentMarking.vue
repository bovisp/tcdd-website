<template>
    <div>
        <assessment-marking-question 
            v-if="!marking"
        />

        <assessment-marking-index 
            v-if="!marking"
        />

        <assessment-marking-participant 
            v-if="marking"
            @assessments:mark-return-to-table="marking = false"
        />
    </div>
</template>

<script>
export default {
    data () {
        return {
            marking: false
        }
    },

    mounted () {
        window.events.$on('assessment:marking', () => {
            this.marking = true
        })
    }
}
</script>