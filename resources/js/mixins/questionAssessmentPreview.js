import { mapGetters, mapActions, mapMutations } from 'vuex'
import { find, orderBy } from 'lodash-es'
import { pascalCase } from 'change-case'

export default {
    props: {
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            parts: [],
            submitting: false,
            editingScore: false,
            score: null 
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        }),

        questionNumber () {
            return this.question.model.assessment_page_content_items[0].question_number
        },

        contentIdForLang () {
            return find(this.question.items[0].question.content_builder, builder => builder.language === this.currentLang)['id']
        },

        totalPoints () {
            return this.question.model.assessment_page_content_items[0].question_score
        },

        questionData () {
            return this.question.items[0].question.question_data
        }
    },

    methods: {
        orderBy,

        pascalCase,

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

            await this. updatePageScore()

            this.question.model.assessment_page_content_items[0].question_score = data

            this.cancelEditScore()
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentIdForLang}`)

        this.parts = data.data.parts
    }
}