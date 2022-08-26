<template>
    <div v-if="attemptReview">
        <h2 class="font-normal text-2xl my-6">
            {{ trans('js_pages_assessments_assessment_components_assessmentattemptreview.reviewexam') }}
        </h2>

        <table
            v-if="attemptReview.questions"
            class="mb-32"
        >
            <tr
                v-for="question in orderBy(attemptReview.questions, ['question_number'], ['asc'])"
                :key="question.question_number"
            >
                <td class="p-2">
                    <strong>{{ trans('generic.question') }} {{ question.question_number }}: ({{ question.question_score }} {{ trans('generic.points') }})</strong>
                </td>
                
                <td
                    class="p-2" 
                    :class="completionTextClass(question)"
                >
                    {{ completionText(question) }}
                </td>

                <td class="p-2">
                    <button 
                        class="btn btn-text text-blue-700"
                        @click.prevent="goToQuestion(question)"
                    >
                        {{ trans('js_pages_assessments_assessment_components_assessmentattemptreview.gotoquestion') }}
                    </button>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { orderBy, forEach, without } from 'lodash-es'

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
            goToQuestion: 'assessment/goToQuestion',
            setIncompleteQuestions: 'assessment/setIncompleteQuestions'
        }),

        orderBy,

        completionTextClass(question) {
            let existingKeys = without(question.existing_question_keys, 'order')

            return question.required_answer_keys.length === existingKeys.length ? 'text-green-700' : 'text-red-700'
        },

        completionText(question) {
            let existingKeys = without(question.existing_question_keys, 'order')
            if (question.required_answer_keys.length === existingKeys.length) {
                this.setIncompleteQuestions(false)

                return this.trans('js_pages_assessments_assessment_components_assessmentattemptreview.complete')
            }

            this.setIncompleteQuestions(true)

            return this.trans('js_pages_assessments_assessment_components_assessmentattemptreview.incomplete')
        }
    },

    async mounted () {
        await this.fetchReviewData()

        forEach(this.attemptReview.questions, question => {
            if (!question.required_answer_keys.length === question.existing_question_keys.length) {
                this.setInconpleteQuestions(true)    
            }
        })
    }
}
</script>