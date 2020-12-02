<template>
    <div>
        <h4 class="text-lg font-medium mb-3">
            General options
        </h4>

        <div
            class="mb-4"
        >
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="form.multiple_answers"
                >

                <span 
                    class="ml-2"
                    :class="{ 'text-red-500': errors.multiple_answers }"
                >Question can have multiple answers (default: <span class="font-bold">false</span>)</span>
            </label>

            <p
                v-if="errors.multiple_answers"
                v-text="errors.multiple_answers[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>

        <h4 class="text-lg font-medium mb-3">
            Possible answers
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
                    Correct
                </p>

                <input 
                    :type="form.multiple_answers ? 'checkbox' : 'radio'" 
                    :class="form.multiple_answers ? 'form-checkbox' : 'form-radio'"
                    :value="answer.is_correct ? true : false"
                    :checked="answer.is_correct"
                    @change="updateCorrect(answer.id)"
                >
            </div>

            <div class="flex-1">
                <label 
                    :for="`answer-${answer.id}`"
                    class="block text-gray-700 font-bold mb-2"
                >Answer</label>

                <textarea 
                    :id="`answer-${answer.id}`"
                    rows="3"
                    class="form-textarea w-full"
                    @input="updateAnswer(answer.id, $event)"
                >{{ answer.text }}</textarea>

                <div class="flex">
                    <button 
                        class="btn btn-sm btn-text text-sm ml-auto text-red-500"
                        @click.prevent="removeAnswer(answer.id)"
                    >
                        Delete answer
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
                Add 3 more answers...
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
import { forEach, includes, find, trim, filter, isEmpty } from 'lodash-es'

export default {
    data () {
        return {
            form: {},
            answerIncrement: 3
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
        
        addAnswers () {
            for (let i = 0; i < this.answerIncrement; i++) {
                this.form.answers.push({
                    id: uuid_v4(),
                    is_correct: false,
                    text: ''
                })
            }
        },

        updateAnswer (answerId, e) {
            let answer = find(this.form.answers, answer => {
                return answer.id === answerId
            })

            answer.text = trim(e.target.value)
        },

        updateCorrect (answerId, e) {
            let answer = find(this.form.answers, answer => {
                return answer.id === answerId
            })

            answer.is_correct = !answer.is_correct
        },

        removeAnswer (answerId) {
            console.log(answerId)
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