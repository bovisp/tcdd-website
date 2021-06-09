<template>
    <div>
        <div class="flex items-center">
            <h2 class="text-3xl font-medium mb-2">
                {{ participantAnswer.participant_fullname }}
            </h2>

            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                {{ trans('js_pages_assessments_assessments_components_marking_assessmentmarkingparticipant.returnmarkingtable') }}
            </button>
        </div>

        <div 
            class="alert alert-blue my-4"
            v-if="participantAnswer.marked"
        >
            {{ trans('js_pages_assessments_assessments_components_marking_assessmentmarkingparticipant.attemptfullymarked') }} {{ participantAnswer.marked_on }}.
        </div>
        
        <ul>
            <li
                v-for="question in orderBy(participantAnswer.questions, ['question_number'], ['asc'])"
                :key="question.id"
                class="flex mb-6"
            >
                <p class="text-right mr-3 w-6 pt-4">
                    {{ question.question_number }}.
                </p>

                <div class="flex-1 mb-6">
                    <component 
                        v-for="part in orderBy(question.parts, ['sort_order'], ['asc'])"
                        :key="part.id"
                        :is="`Final${ pascalCase(part.builderType.type) }`"
                        :part="part"
                    ></component>

                    <h2 class="mb-4 font-medium text-lg">
                        {{ trans('js_pages_assessments_assessments_components_marking_assessmentmarkingparticipant.answer') }}:
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
            </li>
        </ul>

        <div class="flex items-center">
            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                {{ trans('js_pages_assessments_assessments_components_marking_assessmentmarkingparticipant.returnmarkingtable') }}
            </button>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'
import { orderBy } from 'lodash-es'
import { pascalCase } from 'change-case'

export default {
    computed: {
        ...mapGetters({
            participantAnswer: 'assessments/participantAnswer'
        })
    },

    methods: {
        ...mapMutations({
            clearParticipantAnswer: 'assessments/CLEAR_PARTICIPANT_ANSWER'
        }),

        orderBy,

        pascalCase,

        cancel () {
            this.clearParticipantAnswer()

            this.$emit('assessments:mark-return-to-table')
        }
    }
}
</script>