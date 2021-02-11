<template>
    <div>
        <component 
            :is="`${questionType}QuestionAssessmentPreview`"
            :question="question"
        ></component>
    </div>
</template>

<script>
import { pascalCase } from 'change-case'

export default {
    props: {
        question: {
            type: Object,
            required: true
        }
    },

    computed: {
        questionType () {
            return pascalCase(this.question.items[0].question.question_type.code)
        }
    }
}
</script>