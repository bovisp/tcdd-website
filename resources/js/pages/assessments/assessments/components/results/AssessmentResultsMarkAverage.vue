<template>
    <div>
        {{ averageQuestionScore }}
    </div>
</template>

<script>
import { reduce, find, findIndex } from 'lodash-es'
import { mapGetters } from 'vuex'

export default {
    props: {
        attempts: {
            type: Array,
            required: true
        },
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            assessmentAttempts: [],
            averageQuestionScore: null
        }
    },

    ...mapGetters({
        assessment: 'assessments/assessment',
    }),

    methods: {
        average () {
            return ((reduce(this.assessmentAttempts, (result, value, key) => {
                let mark = find(value.marks, m => m.question_id === this.question.id)

                if (!mark) {
                    return result
                }

                return result + parseFloat(mark.mark) 
            }, 0)) / this.assessmentAttempts.length).toFixed(1)
        }
    },

    mounted () {
        this.assessmentAttempts = this.attempts

        this.averageQuestionScore = this.average()

        window.events.$on('assessment:results-mark-table', async payload => {
            // let attemptIndex = findIndex(this.assessmentAttempts, attempt => attempt.id === payload.attempt.id)
            
            // let markIndex = findIndex(this.assessmentAttempts[attemptIndex].marks, mark => mark.id === payload.mark.id)

            // this.assessmentAttempts[attemptIndex]['marks'][markIndex]['mark'] = payload.score
            this.assessmentAttempts = payload.attempts

            this.averageQuestionScore = await this.average()
        })

        window.events.$on('assessments:recalculate-average', async (attempts) => {
            this.assessmentAttempts = attempts

            this.averageQuestionScore = await this.average()
        })
    }
}
</script>