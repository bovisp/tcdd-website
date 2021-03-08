<template>
    <div class="text-center">
        {{ total }} ({{ percentage }}%)
    </div>
</template>

<script>
import { reduce } from 'lodash-es'

export default {
    props: {
        attempt: {
            type: Object,
            required: true
        }
    },

    computed: {
        total () {
            if (this.attempt) {
                return reduce(this.attempt.marks, function (result, value, key) {
                    return result + value.mark 
                }, 0)
            }
        },

        assessmentTotal () {
            if (this.attempt) {
                return reduce(this.attempt.questions, function (result, value, key) {
                    return result + value.question_score 
                }, 0)
            }
        },

        percentage () {
            return Math.round((this.total / this.assessmentTotal) * 100)
        }
    }
}
</script>