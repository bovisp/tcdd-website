<template>
    <div
        v-if="question"
    >
        <div
            class="flex"
        >
            <div class="mr-2">
                {{ questionNumber }}.
            </div>

            <div 
                class="flex-1 -mt-4"
                v-if="typeof parts !== 'undefined'"
            >
                <component 
                    v-for="part in orderBy(parts, ['sort_order'], ['asc'])"
                    :key="part.id"
                    :is="`Final${ pascalCase(part.builderType.type) }`"
                    :part="part"
                ></component>

                <question-edit-score 
                    :question="question"
                />

                <question-edit-page-number 
                    :question="question"
                    @question:update-score="updateScore"
                />

                <div class="mt-6">
                    <ul>
                        <li
                            v-for="answer in answers"
                            :key="answer.id"
                            class="p-1 rounded mb-1"
                            :class="{ 'bg-green-100' : correctAnswer(answer.id) }"
                        >
                            <label class="flex items-center">
                                <span
                                    v-if="submitting"
                                    class="mr-2 w-4"
                                >
                                    <i 
                                        class="fas fa-check fa-sm text-green-500"
                                        v-if="answeredCorrectly(answer.id)"
                                    ></i>

                                    <i 
                                        class="fas fa-times fa-sm text-red-500"
                                        v-if="answeredIncorrectly(answer.id)"
                                    ></i>
                                </span>

                                <input 
                                    :type="questionData.multiple_answers ? 'checkbox' : 'radio'" 
                                    :class="questionData.multiple_answers ? 'form-checkbox' : 'form-radio'"
                                    v-model="form.answer.answers"
                                    :value="answer.id"
                                >

                                <span 
                                    class="ml-2"
                                >{{ answer.text }}</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="flex w-full mt-4">
                    <button 
                        class="btn btn-blue btn-sm text-sm"
                        @click.prevent="submit"
                        v-if="!submitting"
                    >
                        {{ trans('generic.submit') }}
                    </button>

                    <button 
                        class="btn btn-text btn-sm text-sm ml-auto"
                        @click.prevent="cancel"
                    >
                        {{ trans('generic.cancelpreview') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { map, filter, some } from 'lodash-es'
import questionAssessmentPreview from '../../../../../../mixins/questionAssessmentPreview'

export default {
    mixins: [
        questionAssessmentPreview
    ],

    data () {
        return {
            form: {
                answer: {
                    answers: []
                }
            },
            correct: [],
            answers: []
        }
    },

    methods: {
        cancel () {
            this.form.answer.answers = []
            this.submitting = false
        },

        submit () {
            this.submitting = true
        },

        correctAnswer (answerId) {
            return this.submitting && some(this.correct, correct => correct === answerId)
        },

        answeredCorrectly (answerId) {
            if (typeof this.form.answer.answers === 'number') {
                return this.submitting && this.form.answer.answers === answerId && this.correct.indexOf(answerId) >= 0
            }

            return this.submitting && this.form.answer.answers.indexOf(answerId) >= 0 && this.correct.indexOf(answerId) >= 0
        },

        answeredIncorrectly (answerId) {
            if (typeof this.form.answer.answers === 'number') {
                return this.submitting && this.form.answer.answers === answerId && this.correct.indexOf(answerId) === -1
            }

            return this.submitting && this.form.answer.answers.indexOf(answerId) >= 0 && this.correct.indexOf(answerId) === -1
        }
    },

    async mounted () {
        this.answers = this.shuffleArray(this.question.items[0].question.question_data.answers)

        this.correct = map(
            filter(this.answers, answer => {
                return answer.is_correct
            }), answer => answer.id
        )
    }
}
</script>