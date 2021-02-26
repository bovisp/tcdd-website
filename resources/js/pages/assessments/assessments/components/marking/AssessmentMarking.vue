<template>
    <div>
        <assessment-marking-index 
            v-if="!marking"
        />

        <assessment-marking-participant 
            v-if="marking"
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
            this.marking = !this.marking
        })
    }
}
</script>