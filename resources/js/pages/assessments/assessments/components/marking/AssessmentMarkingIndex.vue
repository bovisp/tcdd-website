<template>
    <div class="flex justify-center">
        <div class="w-full lg:w-1/2">
            Index
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    methods: {
        ...mapActions({
            fetchParticipantAnswers: 'assessments/fetchParticipantAnswers'
        })
    },

    async mounted () {
        await this.fetchParticipantAnswers()
    }
}
</script>