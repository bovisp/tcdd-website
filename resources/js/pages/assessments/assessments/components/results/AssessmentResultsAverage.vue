<template>
    <div>
        {{ averageTotal }} ({{ averagePercentage }}%)
    </div>
</template>

<script>
import { reduce } from 'lodash-es'

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

    computed: {
        averageTotal () {
            return (reduce(this.attempts, function (total, attempt, key) {
                return total + reduce(attempt.marks, function (totalOfMarks, mark, key) {
                    return totalOfMarks + mark.mark
                }, 0)
            }, 0) / this.attempts.length).toFixed(1)
        },

        averagePercentage() {
            return Math.round((this.averageTotal / this.overallTotal) * 100)
        },

        overallTotal () {
            return reduce(this.questions, (result, value, key) => result + value.score, 0)
        }
    }
}
</script>