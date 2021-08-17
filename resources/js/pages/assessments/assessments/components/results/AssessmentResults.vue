<template>
    <div>
        <table
            v-if="!editing"
            class="w-full overflow-auto"
        >
            <thead>
                <tr class="border-b-2">
                    <th></th>

                    <th
                        v-for="q in orderBy(questions, ['number'], ['asc'])"
                        :key="q.number"
                    >Q{{ q.number }}({{ q.score }})</th>

                    <th>{{ trans('generic.total') }} ({{ assessmentTotal(attemptAnswers[0]) }})</th>

                    <th>
                        <assessment-results-show-all 
                            v-if="attemptsMarkingCompleted"
                        />
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="attempt in orderBy(attemptAnswers, ['participant_lastname'], ['asc'])"
                    :key="attempt.id"
                    class="border-b"
                >
                    <td class="align-middle">
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
                            @assessment:update-mark-from-results="updateMark"
                        />
                    </td>

                    <td class="p-2 text-center">
                        <assessment-results-mark-total
                            v-if="attempt.marked"
                            :attempt="attempt"
                        />
                    </td>

                    <td class="align-middle">
                        <assessment-results-show
                            v-if="attempt.marked"
                            :attempt="attempt"
                        />
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr class="border border-b-0 border-l-0 border-r-0">
                    <td class="text-right p-2">
                        <strong>{{ trans('js_pages_assessments_assessments_components_results_assessmentresults.average') }}</strong>
                    </td>

                    <td
                        v-for="quest in questions"
                        :key="quest.id"
                        class="p-2 text-center"
                    >
                        <assessment-results-mark-average 
                            :attempts="attemptAnswers"
                            :question="quest"
                        />
                    </td>

                    <td class="p-2 text-center">
                        <assessment-results-average
                            :attempts="attemptAnswers"
                            :questions="questions"
                        />
                    </td>
                </tr>
            </tfoot>
        </table>

        <div v-else>
            <div class="flex items-center w-full">
                <h2 class="text-3xl font-medium mb-2">
                    {{ trans('generic.edit') }}: {{ trans('generic.question') }} {{ question.question_number }} - {{ participantAnswer.participant_fullname }}
                </h2>

                <button 
                    class="btn btn-text ml-auto"
                    @click.prevent="cancel"
                >
                    <i class="fas fa-chevron-left mr-1"></i>
                    {{ trans('js_pages_assessments_assessments_components_results_assessmentresults.returntoresultstable') }}
                </button>
            </div>

            <div class="my-6">
                <component 
                    v-for="part in orderBy(question.parts, ['sort_order'], ['asc'])"
                    :key="part.id"
                    :is="`Final${ pascalCase(part.builderType.type) }`"
                    :part="part"
                ></component>

                <h2 class="mb-4 font-medium text-lg">
                    {{ trans('generic.answer') }}:
                </h2>

                <component 
                    :is="`${pascalCase(question.type)}QuestionMarking`"
                    :answer="participantAnswer.answers[`question_${question.id}`]"
                    :question="question"
                ></component>

                <assessment-marking-guide 
                    :guide="question.marking_guide"
                    v-if="question.type !== 'multiple_choice'"
                />

                <assessment-marking-comments 
                    :question="question"
                    v-if="question.type !== 'multiple_choice'"
                />

                <assessment-marking-score 
                    :question="question"
                />
            </div>

            <div class="flex items-center w-full">
                <button 
                    class="btn btn-text ml-auto"
                    @click.prevent="cancel"
                >
                    <i class="fas fa-chevron-left mr-1"></i>
                    {{ trans('js_pages_assessments_assessments_components_results_assessmentresults.returntoresultstable') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { map, orderBy, reduce, filter } from 'lodash-es'
import { pascalCase } from 'change-case'

export default {
    data () {
        return {
            questions: [],
            editing: false,
            question: null
        }
    },

    computed: {
        ...mapGetters({
            attemptAnswers: 'assessments/attemptAnswers',
            participantAnswer: 'assessments/participantAnswer',
            assessment: 'assessments/assessment'
        }),

        attemptsMarkingCompleted () {
            return filter(this.attemptAnswers, attempt => attempt.marked).length === this.assessment.participants.length
        }
    },

    methods: {
        ...mapActions({
            setParticipantAnswer: 'assessments/setParticipantAnswer'
        }),

        orderBy,

        pascalCase,

        cancel () {
            this.editing = false

            this.setParticipantAnswer(null)
        },

        async updateMark (payload) {
            this.setParticipantAnswer(payload.attempt)

            this.question = payload.question

            this.editing = true
        },

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