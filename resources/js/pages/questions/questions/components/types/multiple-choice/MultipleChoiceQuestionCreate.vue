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
                    v-model="data.multiple_answers"
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
            v-for="answer in data.answers"
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
                    :type="data.multiple_answers === true ? 'checkbox' : 'radio'" 
                    :class="data.multiple_answers === true ? 'form-checkbox' : 'form-radio'"
                    v-model="answerIds"
                    :value="answer.id"
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
                ></textarea>
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
import { v4 as uuid_v4 } from 'uuid'
import { forEach, includes, find, trim, isEmpty } from 'lodash-es'

export default {
    data () {
        return {
            data: {
                multiple_answers: false,
                answers: []
            },
            answerIncrement: 3,
            answerIds: []
        }
    },

    watch: {
        data: {
            deep: true,

            handler () {
                this.$emit('question-type:update-data', this.data)
            }
        },

        answerIds () {
            forEach(this.data.answers, answer => {
                answer.is_correct = includes(this.answerIds, answer.id) ? true : false
            })
        }
    },

    methods: {
        isEmpty,

        addAnswers () {
            for (let i = 0; i < this.answerIncrement; i++) {
                this.data.answers.push({
                    id: uuid_v4(),
                    is_correct: false,
                    text: ''
                })
            }
        },

        updateAnswer(answerId, e) {
            let answer = find(this.data.answers, answer => {
                return answer.id === answerId
            })

            answer.text = trim(e.target.value)
        }
    },

    mounted () {
        this.$emit('question-type:update-data', this.data)

        this.addAnswers()
    }
}
</script>