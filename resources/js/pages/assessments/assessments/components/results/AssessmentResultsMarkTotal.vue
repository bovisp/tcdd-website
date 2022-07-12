<template>
    <div class="text-center">
        {{ total }} ({{ percentage }}%)
    </div>
</template>

<script>
import { reduce, findIndex } from 'lodash-es'

export default {
    props: {
        attempt: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            assessmentAttempt: null,
            total: null
        }
    },

    computed: {
        assessmentTotal () {
            if (this.assessmentAttempt) {
                return reduce(this.assessmentAttempt.questions, function (result, value, key) {
                    return result + value.question_score 
                }, 0)
            }
        },

        percentage () {
            return Math.round((this.total / this.assessmentTotal) * 100)
        }
    },

    methods: {
        totalScore () {
            if (this.assessmentAttempt) {
                return reduce(this.assessmentAttempt.marks, function (result, value, key) {
                    return result + value.mark 
                }, 0)
            }
        },        

        
    },

    mounted () {
        this.assessmentAttempt = this.attempt

        this.total = this.totalScore()

        window.events.$on('assessment:results-mark-table', async payload => {
            if (!payload.attempt) {
                for await (const attempt of payload.attempts) {
                    if (attempt.id === this.assessmentAttempt.id) {
                        this.assessmentAttempt = attempt
                    }
                }
            } else {
                if (payload.attempt.id === this.assessmentAttempt.id) {
                    // let index = findIndex(this.assessmentAttempt.marks, mark => mark.id === payload.mark.id)

                    // this.assessmentAttempt.marks[index].mark = payload.score
                    this.assessmentAttempt = payload.attempt
                }
            }

            this.total = this.totalScore()
        })
    }
}
</script>