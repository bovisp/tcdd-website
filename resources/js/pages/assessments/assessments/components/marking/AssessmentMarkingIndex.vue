<template>
    <div class="flex justify-center">
        <div class="w-full lg:w-1/2">
            <datatable 
                :data="attemptAnswers"
                :columns="columns"
                :per-page="10"
                :order-keys="['participant_lastname', 'participant_firstname']"
                :order-key-directions="['asc', 'asc']"
                :has-event="true"
                event-text="Mark"
                event="assessment:mark"
            ></datatable>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data () {
        return {
            columns: [
                { field: 'participant_firstname', title: 'First name', sortable: true },
                { field: 'participant_lastname', title: 'Last name', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            attemptAnswers: 'assessments/attemptAnswers'
        })
    },

    methods: {
        ...mapActions({
            fetchParticipantAnswers: 'assessments/fetchParticipantAnswers',
            setParticipantAnswer: 'assessments/setParticipantAnswer'
        })
    },

    async mounted () {
        window.events.$on('assessment:mark', attempt => {
            this.setParticipantAnswer(attempt)

            window.events.$emit('assessment:marking')
        })
    }
}
</script>