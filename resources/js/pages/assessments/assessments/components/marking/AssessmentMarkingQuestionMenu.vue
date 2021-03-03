<template>
    <div class="flex justify-center mb-8">
        <div class="w-full lg:w-1/2">
            <label 
                for="question"
                class="block text-gray-700 font-bold mb-2"
            >
                Select a question to mark...
            </label>

            <div class="relative">
                <select 
                    id="question"
                    v-model="question"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                >
                    <option value=""></option>

                    <option
                        :value="q.id"
                        v-for="q in questions"
                        :key="q.id"
                        v-text="`Question ${q.question_number}`"
                    ></option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { find } from 'lodash-es'

export default {
    data () {
        return {
            questions: [],
            question: null
        }
    },

    computed: {
        ...mapGetters({
            attemptAnswers: 'assessments/attemptAnswers'
        })
    },

    watch: {
        question () {
            if (this.question) {
                window.events.$emit('assessment:mark-question', {
                    question: find(this.attemptAnswers[0].questions, q => q.id === this.question),
                    key: `question_${this.question}`
                })
            }
        }
    },

    mounted () {
        this.questions = this.attemptAnswers[0].questions
    }
}
</script>