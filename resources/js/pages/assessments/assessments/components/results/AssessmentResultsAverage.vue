<template>
    <div>
        {{ averageTotal }} ({{ averagePercentage }}%)
    </div>
</template>

<script>
import { reduce, findIndex } from 'lodash-es'

export default {
    props: {
        attempts: {
            type: Array,
            required: true
        },
        questions: {
            type: Array,
            required: true
        }
    },

    data () {
        return {
            assessmentAttempts: [],
            averageTotal: null,
            averagePercentage: null
        }
    },

    computed: {
        overallTotal () {
            return reduce(this.questions, (result, value, key) => result + value.score, 0)
        }
    },

    methods: {
        getAverageTotal () {
            return (reduce(this.assessmentAttempts, function (total, attempt, key) {
                return total + reduce(attempt.marks, function (totalOfMarks, mark, key) {
                    return totalOfMarks + mark.mark
                }, 0)
            }, 0) / this.assessmentAttempts.length).toFixed(1)
        },

        getAveragePercentage() {
            return Math.round((this.averageTotal / this.overallTotal) * 100)
        }
    },

    async mounted () {
        this.assessmentAttempts = this.attempts

        this.averageTotal = await this.getAverageTotal()

        this.averagePercentage = await this.getAveragePercentage()

        window.events.$on('assessment:results-mark-table', async payload => {
            // let attemptIndex = findIndex(this.assessmentAttempts, attempt => attempt.id === payload.attempt.id)
            
            // let markIndex = findIndex(this.assessmentAttempts[attemptIndex].marks, mark => mark.id === payload.mark.id)

            // this.assessmentAttempts[attemptIndex]['marks'][markIndex]['mark'] = payload.score
            this.assessmentAttempts = payload.attempts

            this.averageTotal = await this.getAverageTotal()

            this.averagePercentage = await this.getAveragePercentage()
        })

        window.events.$on('assessments:recalculate-average', async (attempts) => {
            this.assessmentAttempts = attempts

            this.averageTotal = await this.getAverageTotal()

            this.averagePercentage = await this.getAveragePercentage()
        })
    }
}
</script>