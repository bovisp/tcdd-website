<template>
    <div class="flex justify-center">
        <table>
            <thead>
                <tr>
                    <th></th>

                    <th
                        v-for="q in orderBy(questions, ['number'], ['asc'])"
                        :key="q.number"
                    >Q{{ q.number }}({{ q.score }})</th>

                    <th>Total ({{ assessmentTotal(attemptAnswers[0]) }})</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="attempt in orderBy(attemptsMarkingCompleted, ['participant_lastname'], ['asc'])"
                    :key="attempt.id"
                >
                    <td>
                        <strong>{{ attempt.participant_fullname }}</strong>
                    </td>

                    <td
                        v-for="q in orderBy(attempt.questions, ['question_number'], ['asc'])"
                        :key="q.id"
                        class="p-2 text-center"
                    >
                        <assessment-results-mark
                            :attempt="attempt"
                            :question="q"
                        />
                    </td>

                    <td class="p-2 text-center">
                        <assessment-results-mark-total
                            :attempt="attempt"
                        />
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr class="border border-b-0 border-l-0 border-r-0">
                    <td class="text-right p-2">
                        <strong>Average</strong>
                    </td>

                    <td
                        v-for="quest in questions"
                        :key="quest.id"
                        class="p-2 text-center"
                    >
                        <assessment-results-mark-average 
                            :attempts="attemptsMarkingCompleted"
                            :question="quest"
                        />
                    </td>

                    <td class="p-2 text-center">
                        <assessment-results-average
                            :attempts="attemptsMarkingCompleted"
                            :questions="questions"
                        />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { map, orderBy, reduce, filter } from 'lodash-es'

export default {
    data () {
        return {
            questions: []
        }
    },

    computed: {
        ...mapGetters({
            attemptAnswers: 'assessments/attemptAnswers'
        }),

        attemptsMarkingCompleted () {
            return filter(this.attemptAnswers, attempt => attempt.marked)
        }
    },

    methods: {
        orderBy,

        assessmentTotal (attempt) {
            if (attempt) {
                return reduce(attempt.questions, function (result, value, key) {
                    return result + value.question_score 
                }, 0)
            }
        }
    },

    mounted () {
        this.questions = map(this.attemptAnswers[0].questions, question => {
            return { 
                number: question.question_number, 
                score: question.question_score,
                id: question.id 
            }
        })
    }
}
</script>