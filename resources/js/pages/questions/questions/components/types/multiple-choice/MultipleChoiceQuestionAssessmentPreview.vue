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

                <div class="flex items-center">
                    <strong class="text-gray-700 mr-1">Points:</strong> 
                    
                    <template v-if="!editingScore">
                        {{ totalPoints }} 
                        <button 
                            class="btn btn-text text-sm btn-sm text-blue-500 ml-2"
                            @click.prevent="editScore"
                        >Edit</button>
                    </template>

                    <template v-else>
                        <input 
                            type="text"
                            class="shadow appearance-none border rounded w-32 py-1 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm"
                            v-model="score"
                        >

                        <button 
                            class="btn btn-blue text-sm btn-sm ml-2"
                            @click.prevent="changeScore"
                        >Change</button>

                        <button 
                            class="btn btn-text text-sm btn-sm ml-2"
                            @click.prevent="cancelEditScore"
                        >Cancel</button>
                    </template>
                </div>

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
                        Submit
                    </button>

                    <button 
                        class="btn btn-text btn-sm text-sm ml-auto"
                        @click.prevent="cancel"
                    >
                        Cancel preview
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { find, orderBy, map, filter, some } from 'lodash-es'
import { pascalCase } from 'change-case'

export default {
    props: {
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            parts: [],
            form: {
                answer: {
                    answers: []
                }
            },
            submitting: false,
            correct: [],
            answers: [],
            editingScore: false,
            score: null
        }
    },

    computed: {
        questionNumber () {
            return this.question.model.assessment_page_content_items[0].question_number
        },

        contentIdForLang () {
            return find(this.question.items[0].question.content_builder, builder => builder.language === this.currentLang)['id']
        },

        questionData () {
            return this.question.items[0].question.question_data
        },

        totalPoints () {
            return this.question.model.assessment_page_content_items[0].question_score
        }
    },

    methods: {
        orderBy,

        pascalCase,

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
        },

        editScore () {
            this.editingScore = true

            this.score = this.question.model.assessment_page_content_items[0].question_score
        },

        cancelEditScore () {
            this.editingScore = false

            this.score = null
        },

        async changeScore () {
            let assessmentPageContentItemId = this.question.model.assessment_page_content_items[0].id

            let { data } = await axios.patch(`${this.urlBase}/api/assessments/questions/${assessmentPageContentItemId}/change-score`, {
                score: this.score
            })

            this.question.model.assessment_page_content_items[0].question_score = data

            this.cancelEditScore()
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentIdForLang}`)

        this.parts = data.data.parts

        this.answers = this.shuffleArray(this.question.items[0].question.question_data.answers)

        this.correct = map(
            filter(this.answers, answer => {
                return answer.is_correct
            }), answer => answer.id
        )
    }
}
</script>