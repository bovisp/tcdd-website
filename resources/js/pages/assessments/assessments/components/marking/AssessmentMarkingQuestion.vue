<template>
    <div>
        <div class="flex items-center">
            <h3 class="text-3xl font-medium mb-4">
                {{ trans('js_pages_assessments_assessments_components_marking_assessmentmarkingquestion.markingquestion') }} {{ questionMarkingObj.question.question_number }}
            </h3>

            <button 
                class="btn btn-text ml-auto"
                @click.prevent="$emit('assessments:mark-return-to-table')"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                {{ trans('generic.returnmarkingtable') }}
            </button>
        </div>

        <component 
            v-for="part in orderBy(questionMarkingObj.question.parts, ['sort_order'], ['asc'])"
            :key="part.id"
            :is="`Final${ pascalCase(part.builderType.type) }`"
            :part="part"
        ></component>

        <assessment-marking-guide 
            :guide="questionMarkingObj.question.marking_guide"
            v-if="questionMarkingObj.question.type !== 'multiple_choice'"
        />

        <ul>
            <li
                v-for="answer in attemptAnswers"
                :key="answer.id"
                class="mb-8"
            >
                <h3 class="text-xl font-medium mb-2">
                    {{ answer.participant_fullname }}
                </h3>

                <component 
                    :is="`${pascalCase(questionMarkingObj.question.type)}QuestionMarking`"
                    :answer="answer.answers[`question_${questionMarkingObj.question.id}`]"
                    :question="questionMarkingObj.question"
                ></component>

                <assessment-marking-comments 
                    :question="questionMarkingObj.question"
                    v-if="questionMarkingObj.question.type !== 'multiple_choice'"
                    :answer="answer"
                />

                <assessment-marking-score 
                    :question="questionMarkingObj.question"
                    :answer="answer"
                />
            </li>
        </ul>

        <div class="flex items-center">
            <button 
                class="btn btn-text ml-auto"
                @click.prevent="$emit('assessments:mark-return-to-table')"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                {{ trans('generic.returnmarkingtable') }}
            </button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { pascalCase } from 'change-case'
import { orderBy } from 'lodash-es'

export default {
    props: {
        questionMarkingObj: {
            type: Object,
            required: true
        }
    },

    computed: {
        ...mapGetters({
            attemptAnswers: 'assessments/attemptAnswers'
        })
    },

    methods: {
        orderBy,

        pascalCase
    },

    mounted () {
        // 
    }
}
</script>