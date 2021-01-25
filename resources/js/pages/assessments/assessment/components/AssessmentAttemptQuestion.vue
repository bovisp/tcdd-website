<template>
    <div v-if="data">
        <p
            v-if="data.items"
            class="-mb-3 text-lg"
        >
            <strong>
                Question: {{ data.items[0].question_number }} ({{ data.items[0].question_score }} points)
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
import { mapGetters } from 'vuex'
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
        pascalCase
    },

    async mounted () {
        let { data: question } = await axios.get(`
            ${this.urlBase}/api/assessment/${this.attempt.assessment.id}/assessment/${this.attempt.id}/question/${this.data.items[0].model_id}
        `)

        this.question = question
    }
}
</script>