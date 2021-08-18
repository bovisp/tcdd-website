<template>
    <div class="flex items-center">
        <strong class="text-gray-700 mr-1">
            {{ trans('generic.points') }}:
        </strong> 
        
        <template v-if="!editingScore">
            {{ totalPoints }} 
            <button 
                class="btn btn-text text-sm btn-sm text-blue-500 ml-2"
                @click.prevent="editScore"
                v-if="!assessment.locked"
            >{{ trans('generic.edit') }}</button>
        </template>

        <template v-if="editingScore && !assessment.locked">

            <b-field 
                :type="errors.score ? 'is-danger' : ''"
                :message="errors.score ? errors.score[0] : ''"
                class="mb-0"
            >
                <b-input 
                    v-model="score"
                    type="number"
                    size="is-small"
                ></b-input>
            </b-field>

            <button 
                class="btn btn-blue text-sm btn-sm ml-2"
                @click.prevent="changeScore"
            >{{ trans('generic.change') }}</button>

            <button 
                class="btn btn-text text-sm btn-sm ml-2"
                @click.prevent="cancelEditScore"
            >{{ trans('generic.cancel') }}</button>
        </template>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'

export default {
    props: {
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            editingScore: false,
            score: null 
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        }),

        totalPoints () {
            return this.question.model.assessment_page_content_items[0].question_score
        },
    },

    methods: {
        ...mapActions({
            fetchPages: 'assessments/fetchPages'
        }),

        ...mapMutations({
            updatePage: 'assessments/SET_CURRENT_PAGE',
            updatePageScore: 'assessments/SET_CURRENT_PAGE_SCORE'
        }),

        editScore () {
            this.editingScore = true

            this.score = this.question.model.assessment_page_content_items[0].question_score
        },

        cancelEditScore () {
            this.editingScore = false

            this.score = null
        },

        async changeScore () {
            let assessmentPageContentItemId = this.question.model.assessment_page_content_items[0].id

            let { data } = await axios.patch(`${this.urlBase}/api/assessments/${this.assessment.id}/questions/${assessmentPageContentItemId}/change-score`, {
                score: this.score
            })

           window.events.$emit('assessment:question-score-change')

            this.cancelEditScore()
        }
    }
}
</script>