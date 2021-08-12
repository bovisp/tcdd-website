<template>
    <div class="flex justify-center mb-8">
        <b-field
            :label="trans('js_pages_assessments_assessments_components_marking_assessmentmarkingquestionmenu.selectquestion')"
        >
            <b-select 
                expanded
                v-model="question"
            >
                <option
                    :value="q.id"
                    v-for="q in attemptAnswers"
                    :key="q.id"
                    v-text="`${trans('generic.question')} ${q.question_number}`"
                ></option>
            </b-select>
        </b-field>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { find } from 'lodash-es'

export default {
    data () {
        return {
            // questions: [],
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
        },

        // attemptAnswers: {
        //     deep: true,

        //     handler () {
        //         this.questions = this.attemptAnswers[0].questions
        //     }
        // }
    },

    // mounted () {
    //     this.questions = this.attemptAnswers[0].questions
    // }
}
</script>