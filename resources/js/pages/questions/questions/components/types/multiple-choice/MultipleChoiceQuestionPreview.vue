<template>
    <div class="flex mx-auto flex-col w-2/3">
        <div class="flex w-full mb-6">
            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="$emit('question-preview:cancel')"
            >
                {{ trans('generic.cancelpreview') }}
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
                :id="id"
                :data="part"
            ></component>
        </template>

        <div>
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
                            <b-icon 
                                class="text-green-500"
                                icon="check"
                                size="is-small"
                                v-if="answeredCorrectly(answer.id)"
                            ></b-icon>

                            <b-icon 
                                class="text-red-500"
                                icon="close"
                                size="is-small"
                                v-if="answeredIncorrectly(answer.id)"
                            ></b-icon>
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
            {{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionpreview.cancelpreviewtext') }}
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
</template>

<script>
import { orderBy, map, filter, some } from 'lodash-es'
import { mapActions, mapGetters } from 'vuex'
import { pascalCase } from 'change-case'

export default {
    props: {
        id: {
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
            correct: [],
            answers: []
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
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.id}`)

        this.parts = data.data.parts

        await this.fetchTestQuestionData()

        this.answers = this.testQuestionData.questionTypeData.answers

        this.correct = map(
            filter(this.answers, answer => {
                return answer.is_correct
            }), answer => answer.id
        )
    }
}
</script>