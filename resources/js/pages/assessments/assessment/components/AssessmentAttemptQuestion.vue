<template>
    <div v-if="data">
        <p
            v-if="data.items"
            class="-mb-3 text-lg"
        >
            <strong>
                {{ trans('js_pages_assessments_assessment_components_assessmentattemptquestion.question') }}: {{ data.items[0].question_number }} ({{ data.items[0].question_score }} {{ trans('js_pages_assessments_assessment_components_assessmentattemptquestion.points') }})
            </strong>
        </p>

        <component
            v-if="question && question.type"
            :is="`${pascalCase(question.type)}QuestionFinal`"
            :data="question"
        ></component>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { pascalCase } from 'change-case'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            question: {}
        }
    },

    computed: {
        ...mapGetters({
            attempt: 'assessment/attempt'
        })
    },

    methods: {
        ...mapActions({
            pushMultipleChoiceData: 'assessment/pushMultipleChoiceData'
        }),

        pascalCase
    },

    async mounted () {
        let { data: question } = await axios.get(`
            ${this.urlBase}/api/assessment/${this.attempt.assessment.id}/attempt/${this.attempt.id}/question/${this.data.items[0].model_id}
        `)

        if (question.data.type === 'multiple_choice') {
            await this.pushMultipleChoiceData({
                id: question.data.id,
                answers: question.data.data.answers
            })
        }

        this.question = question.data
    }
}
</script>