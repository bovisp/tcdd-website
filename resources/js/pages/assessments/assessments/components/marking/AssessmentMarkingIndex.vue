<template>
    <div class="flex justify-center">
        <div class="w-full lg:w-2/3">
            <datatable 
                :data="attemptAnswers"
                :columns="columns"
                :per-page="10"
                :order-keys="['participant_lastname', 'participant_firstname']"
                :order-key-directions="['asc', 'asc']"
                :has-event="true"
                :event-text="trans('js_pages_assessments_assessments_components_marking_assessmentmarkingindex.mark')"
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
                { field: 'participant_firstname', title: this.trans('js_pages_assessments_assessments_components_marking_assessmentmarkingindex.firstname'), sortable: true },
                { field: 'participant_lastname', title: this.trans('js_pages_assessments_assessments_components_marking_assessmentmarkingindex.lastname'), sortable: true },
                { field: 'marked_on', title: this.trans('js_pages_assessments_assessments_components_marking_assessmentmarkingindex.markingcompleted'), sortable: false }
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