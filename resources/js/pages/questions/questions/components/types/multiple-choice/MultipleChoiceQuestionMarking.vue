<template>
    <div>
        <ul>
            <li
                v-for="a in orderBy(question.answers, ['id'], ['asc'])"
                :key="a.id"
                class="p-1 rounded mb-1 flex"
                :class="{ 'bg-green-100' : correctAnswer(a.id) }"
            >
                <span
                    class="mr-2 w-4"
                >
                    <b-icon 
                        class="text-green-500"
                        icon="check"
                        size="is-small"
                        v-if="answeredCorrectly(a.id)"
                    ></b-icon>

                    <b-icon 
                        class="text-red-500"
                        icon="close"
                        size="is-small"
                        v-if="answeredIncorrectly(a.id)"
                    ></b-icon>
                </span>

                <span 
                    class="ml-2"
                >{{ a.text }}</span>
            </li>
        </ul>
    </div>
</template>

<script>
import questionMarking from '../../../../../../mixins/questionMarking'
import { orderBy, map, filter, some, indexOf } from 'lodash-es'

export default {
    mixins: [ questionMarking ],

    data () {
        return {
            correct: []
        }
    },
    
    methods: {
        orderBy,

        correctAnswer (answerId) {
            return some(this.correct, correct => correct === answerId)
        },

        answeredCorrectly (answerId) {
            if (typeof this.answer.answers.data === 'number') {
                return this.answer.answers.data === answerId && this.correct.indexOf(answerId) >= 0
            }

            return indexOf(this.answer.answers.data, answerId) >= 0 && indexOf(this.correct, answerId) >= 0
        },

        answeredIncorrectly (answerId) {
            if (typeof this.answer.answers.data === 'number') {
                return this.answer.answers.data === answerId && this.correct.indexOf(answerId) === -1
            }

            return indexOf(this.answer.answers.data, answerId) >= 0 && indexOf(this.correct, answerId) === -1
        }
    },

    mounted () {
        this.correct = map(
            filter(this.question.answers, answer => {
                return answer.is_correct
            }), answer => answer.id
        )
    }
}
</script>