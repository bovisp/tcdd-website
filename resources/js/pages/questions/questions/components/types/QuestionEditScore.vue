<template>
    <div class="flex items-center">
        <strong class="text-gray-700 mr-1">Points:</strong> 
        
        <template v-if="!editingScore">
            {{ totalPoints }} 
            <button 
                class="btn btn-text text-sm btn-sm text-blue-500 ml-2"
                @click.prevent="editScore"
                v-if="!lockStatus"
            >Edit</button>
        </template>

        <template v-if="editingScore && !lockStatus">
            <input 
                type="text"
                class="shadow appearance-none border rounded w-32 py-1 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm"
                v-model="score"
            >

            <button 
                class="btn btn-blue text-sm btn-sm ml-2"
                @click.prevent="changeScore"
            >Change</button>

            <button 
                class="btn btn-text text-sm btn-sm ml-2"
                @click.prevent="cancelEditScore"
            >Cancel</button>
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
            assessment: 'assessments/assessment',
            lockStatus: 'assessments/lockStatus'
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

            await this.fetchPages(this.assessment.id)

            await this.updatePage()

            await this.updatePageScore()

            this.$emit('question:update-score', data)

            this.cancelEditScore()
        }
    }
}
</script>