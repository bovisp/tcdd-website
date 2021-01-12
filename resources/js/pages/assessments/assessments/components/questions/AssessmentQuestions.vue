<template>
    <div>
        <assessment-pages 
            :pages="pages"
            @assessment-pages:add="addPage"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { map } from 'lodash-es'

export default {
    data () {
        return {
            pages: []
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        addPage (page) {
            this.pages.push(page)
        },

        async fetch () {
            let { data } = await axios.get(`${this.urlBase}/api/assessments/${this.assessment.id}/page`)

            this.pages = data.data

            await this.getTotalScore()
        },

        async getTotalScore () {
            let totalScore = 0

            for await (let page of this.pages) {
                let pageItems = page.data

                for await (let pageItem of pageItems) {
                    if (pageItem.type === 'Question') {
                        totalScore += pageItem.model.assessment_page_content_items[0].question_score
                    }
                }
            }

            window.events.$emit('assessment:total-score', totalScore)
        }
    },

    async mounted () {
        await this.fetch()

        window.events.$on('assessment-pages:reload', async () => {
            await this.fetch()
        })
    }
}
</script>