<template>
    <div class="flex items-center">
        {{ markScore }}

        <b-button 
            type="is-text"
            size="is-small"
            icon-right="pencil"
            v-if="markScore >= 0"
            :title="`${trans('js_pages_assessments_assessments_components_results_assessmentmark.editquestion')} ${question.question_number} ${trans('js_pages_assessments_assessments_components_results_assessmentmark.scorefor')} ${attempt.participant_fullname}`"
            @click.prevent="$emit('assessment:update-mark-from-results', { attempt, mark, question })"
        ></b-button>
    </div>
</template>

<script>
import { find } from 'lodash-es'

export default {
    props: {
        attempt: {
            type: Object,
            required: true
        },
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            // editing: false,
            mark: null,
            markScore: null
        }
    },

    // methods: {
    //     ...mapActions({
    //         updateMarkScore: 'assessments/updateMarkScore'
    //     }),

    //     async update () {
    //         await this.updateMarkScore({
    //             attempt: this.attempt,
    //             mark: this.mark,
    //             score: this.markScore
    //         })

    //         this.editing = false

    //         window.events.$emit('assessment:results-mark-table', {
    //             attempt: this.attempt,
    //             mark: this.mark,
    //             score: parseFloat(this.markScore)
    //         })
    //     }
    // },

    mounted () {
        this.mark = find(this.attempt.marks, ['question_id', this.question.id])

        if (this.mark) {
            this.markScore = parseFloat(this.mark.mark)
        }   

        window.events.$on('assessment:results-mark-table', payload => {
            if (this.attempt && this.question) {
                if (payload.mark.question_id === this.question.id && this.attempt.id === payload.mark.assessment_attempt_id) {
                    this.mark = payload.mark
                    this.markScore = parseFloat(payload.mark.mark)
                }
            }
        })
        
        window.events.$on('assessment:mark-update-attempt', payload => {
            if (this.attempt && this.question) {
                if (payload.data.question_id === this.question.id && this.attempt.id === payload.data.assessment_attempt_id) {
                    this.mark = payload.data
                    this.markScore = parseFloat(payload.data.mark)
                }
            }
        })
    }
}
</script>