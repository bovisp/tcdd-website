<template>
    <div v-if="attemptReview">
        <h2 class="font-normal text-2xl my-6">
            Review your exam
        </h2>

        <ul
            v-if="attemptReview.questions"
        >
            <li
                v-for="question in orderBy(attemptReview.questions, ['question_number'], ['asc'])"
                :key="question.question_number"
            >
                <strong>Question {{ question.question_number }}: ({{ question.question_score }} points)</strong>
                
                <span 
                    class="mx-2"
                    :class="completionTextClass(question)"
                >{{ completionText(question) }}</span>

                <button 
                    class="btn btn-text text-blue-700"
                    @click.prevent="goToQuestion(question)"
                >
                    Go to question
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { orderBy } from 'lodash-es'

export default {
    data () {
        return {
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            attemptReview: 'assessment/attemptReview'
        })
    },

    methods: {
        ...mapActions({
            fetchReviewData: 'assessment/fetchReviewData',
            goToQuestion: 'assessment/goToQuestion'
        }),

        orderBy,

        completionTextClass(question) {
            return question.required_answer_keys.length === question.existing_question_keys.length ? 'text-green-700' : 'text-red-700'
        },

        completionText(question) {
            console.log(question)
            return question.required_answer_keys.length === question.existing_question_keys.length ? 'Complete' : 'Incomplete'
        }
    },

    async mounted () {
        await this.fetchReviewData()

        console.log()
    }
}
</script>