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
            submitting: false
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

        questionData () {
            return this.question.items[0].question.question_data
        }
    },

    methods: {
        orderBy,

        pascalCase,

        updateScore (score) {
            this.question.model.assessment_page_content_items[0].question_score = score
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentIdForLang}`)

        this.parts = data.data.parts
    }
}