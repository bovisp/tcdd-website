<template>
    <div v-if="typeof form !== 'undefined'">
        <h4 class="text-lg font-medium mb-3">
            {{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.generaloptions') }}
        </h4>

        <div
            class="mb-4"
        >
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="form.multiple_answers"
                    @change="test"
                >

                <span 
                    class="ml-2"
                    :class="{ 'text-red-500': errors.multiple_answers }"
                >{{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.questionmultipleanswers') }} ({{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.default') }}: <span class="font-bold">{{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.false') }}</span>)</span>
            </label>

            <p
                v-if="errors.multiple_answers"
                v-text="errors.multiple_answers[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>

        <div
            class="mb-4"
        >
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="form.shuffle_answers"
                >

                <span 
                    class="ml-2"
                    :class="{ 'text-red-500': errors.shuffle_answers }"
                >{{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.answersrandomlyshuffled') }} ({{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.default') }}: <span class="font-bold">{{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.true') }}</span>)</span>
            </label>

            <p
                v-if="errors.shuffle_answers"
                v-text="errors.shuffle_answers[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>

        <h4 class="text-lg font-medium mb-3">
            {{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.possibleanswers') }}
        </h4>

        <div
            v-for="answer in form.answers"
            :key="answer.id"
            class="flex w-full mb-4"
        >
            <div
                class="w-32 flex flex-col items-center justify-start"
            >
                <p class="block text-gray-700 font-bold mb-2">
                    {{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.correct') }}
                </p>

                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    :value="answer.id"
                    :checked="answer.is_correct"
                    @change="updateCorrect(answer.id)"
                >
            </div>

            <div class="flex-1">
                <div class="mb-2">
                    <label 
                        :for="`answer-${answer.id}-en`"
                        class="block text-gray-700 font-bold mb-2"
                    >{{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.answerenglish') }}</label>

                    <textarea 
                        :id="`answer-${answer.id}-en`"
                        rows="3"
                        class="form-textarea w-full"
                        @input="updateAnswer(answer.id, 'en', $event)"
                    >{{ answer.text_en }}</textarea>
                </div>

                <div>
                    <label 
                        :for="`answer-${answer.id}-fr`"
                        class="block text-gray-700 font-bold mb-2"
                    >{{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.answerfrench') }}</label>

                    <textarea 
                        :id="`answer-${answer.id}-fr`"
                        rows="3"
                        class="form-textarea w-full"
                        @input="updateAnswer(answer.id, 'fr', $event)"
                    >{{ answer.text_fr }}</textarea>
                </div>

                <div class="flex">
                    <button 
                        class="btn btn-sm btn-text text-sm ml-auto text-red-500"
                        @click.prevent="removeAnswer(answer.id)"
                    >
                        {{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.deleteanswer') }}
                    </button>
                </div>
            </div>
        </div>

        <div
            class="flex w-full"
        >
            <button 
                class="btn btn-blue btn-sm text-sm ml-auto"
                @click.prevent="addAnswers"
            >
                {{ trans('js_pages_questions_questions_components_types_multiplechoice_multiplechoicequestionedit.addmoreanswers') }}...
            </button>
        </div>

        <div 
            class="alert alert-red content mt-4"
            v-if="!isEmpty(errors)"
            v-text="errors.answers[0]"
        ></div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { v4 as uuid_v4 } from 'uuid'
import { find, trim, filter, isEmpty, set } from 'lodash-es'

export default {
    data () {
        return {
            form: {},
            answerIncrement: 3,
            count: 0
        }
    },

    computed: {
        ...mapGetters({
            questionTypeData: 'questions/questionTypeData'
        })
    },

    watch: {
        form: {
            deep: true,

            handler () {
                this.$emit('question-type:update-data', this.form)
            }
        },

        questionTypeData: {
            deep: true,

            handler () {
                this.form = this.questionTypeData.data
            }
        }
    },

    methods: {
        isEmpty,

        test () {
            for (let i = 0; i < this.form.answers.length; i++) {
                set(this.form.answers[i], 'is_correct', false)
            }
        },
        
        addAnswers () {
            for (let i = 0; i < this.answerIncrement; i++) {
                this.form.answers.push({
                    id: uuid_v4(),
                    is_correct: false,
                    text_en: '',
                    text_fr: ''
                })
            }
        },

        updateAnswer (answerId, lang, e) {
            let answer = find(this.form.answers, answer => {
                return answer.id === answerId
            })

            answer[`text_${lang}`] = trim(e.target.value)
        },

        async updateCorrect (answerId, e) {
            if (!this.questionTypeData.data.multiple_answers) {
                for await (let answer of this.form.answers) {
                    answer.is_correct = false
                }
            }

            let answer = find(this.form.answers, answer => {
                return answer.id === answerId
            })

            answer.is_correct = !answer.is_correct
        },

        removeAnswer (answerId) {
            this.form.answers = filter(this.form.answers, answer => {
                return answer.id !== answerId
            })
        }
    },

    mounted () {
        this.form = this.questionTypeData.data

        this.$emit('question-type:update-data', this.form)
    }
}
</script>