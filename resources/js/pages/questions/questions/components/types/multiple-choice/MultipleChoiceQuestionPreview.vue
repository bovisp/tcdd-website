<template>
    <div class="flex mx-auto flex-col w-2/3">
        <div class="flex w-full mb-6">
            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="$emit('question-preview:cancel')"
            >
                Cancel preview
            </button>
        </div>

        <h1 class="font-light text-4xl mb-4">
            {{ testQuestionData.name }}
        </h1>

        <template v-if="typeof parts !== 'undefined'">
            <component 
                v-for="part in orderBy(parts, ['sort_order'], ['asc'])"
                :key="part.id"
                :is="`Final${ pascalCase(part.builderType.type) }`"
                :part="part"
            ></component>
        </template>

        <div
            v-if="typeof testQuestionData.questionTypeData !== 'undefined' && typeof testQuestionData.questionTypeData.answers !== 'undefined'"
        >
            <ul>
                <li
                    v-for="answer in shuffleArray(testQuestionData.questionTypeData.answers)"
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
                            :type="testQuestionData.questionTypeData.multiple_answers ? 'checkbox' : 'radio'" 
                            :class="testQuestionData.questionTypeData.multiple_answers ? 'form-checkbox' : 'form-radio'"
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

        <div 
            class="alert alert-blue mt-4"
            v-if="submitting"
        >
            The correct answers are highlighted in green. Please click the "Cancel preview" button to finish.
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
</template>

<script>
import { orderBy, map, filter, some } from 'lodash-es'
import { mapActions, mapGetters } from 'vuex'
import { pascalCase } from 'change-case'

export default {
    props: {
        contentId: {
            type: Number,
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
            correct: []
        }
    },

    computed: {
        ...mapGetters({
            testQuestionData: 'questions/testQuestionData'
        })
    },

    methods: {
        pascalCase,

        orderBy,

        ...mapActions({
            fetchTestQuestionData: 'questions/fetchTestQuestionData'
        }),

        submit () {
            this.submitting = true
        },

        cancel () {
            this.form.answer.answers = []
            this.submitting = false

            this.$emit('question-preview:cancel')
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
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentId}`)

        this.parts = data.data.parts

        await this.fetchTestQuestionData()

        this.correct = map(
            filter(this.testQuestionData.questionTypeData.answers, answer => {
                return answer.is_correct
            }), answer => answer.id
        )
    }
}
</script>