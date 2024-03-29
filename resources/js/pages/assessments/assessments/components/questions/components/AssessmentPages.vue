<template>
    <div v-if="assessment">
        <div class="level">
            <div class="level-left">
                <strong class="mr-1">{{ trans('generic.totalscore') }}:</strong> {{ totalScore }}
            </div>

            <div class="level-right">
                <div class="level-item">
                    <b-button
                        type="is-info"
                        icon-left="plus"
                        @click.prevent="add"
                        :disabled="assessment.locked === true"
                    >{{ trans('generic.addpage') }}</b-button>
                </div>

                <div 
                    class="level-item"
                    v-if="assessment.pages"
                >
                    <b-field>
                        <b-select v-model="pageNumber">
                            <option
                                :value="p"
                                v-for="p in assessment.pages"
                                :key="p"
                                v-text="`${trans('generic.page')} ${p}`"
                            ></option>
                        </b-select>
                    </b-field>
                </div>
            </div>
        </div>

        <template v-if="assessment.pages">
            <hr class="my-4">

            <assessment-page />
        </template>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data () {
        return {
            pageNumber: null
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment',
            page: 'assessments/page',
            totalScore: 'assessments/totalScore'
        })
    },

    watch: {
        async pageNumber () {
            await this.fetchPage(this.pageNumber)

            this.pageNumber = this.page.number
        }
    },

    methods: {
        ...mapActions({
            fetchPage: 'assessments/fetchPage',
            addPage: 'assessments/addPage'
        }),

        async add () {
            await this.addPage()

            this.pageNumber = this.page.number
        }
    },

    async mounted () {
        await this.fetchPage()

        if (this.page) {
            this.pageNumber = this.page.number
        }

        window.events.$on('page:deleted', () => {
            this.pageNumber = null
        })

        window.events.$on('page:update', pageNumber => this.pageNumber = pageNumber)

        window.events.$on('assessment:question-added', async () => {
            await this.fetchPage(this.pageNumber)

            this.$buefy.toast.open({
                message: 'Question successfully added to page.',
                type: 'is-success'
            })
        })

        window.events.$on('assessment:content-added', async () => {
            await this.fetchPage(this.pageNumber)

            this.$buefy.toast.open({
                message: 'Content successfully added to page.',
                type: 'is-success'
            })
        })

        window.events.$on('page:item-reorder', () => this.fetchPage(this.pageNumber))

        window.events.$on('assessment:question-page-change', (pageNumber) => {
            this.pageNumber = pageNumber

            this.$scrollTo('#page-top')
        })

        window.events.$on('assessment:question-score-change', () => this.fetchPage(this.pageNumber))

        window.events.$on('page:item-delete', async () => {
            await this.fetchPage(this.pageNumber)

            this.$scrollTo('#page-top')
        })

        window.events.$on('page:question-edit', () => this.fetchPage(this.pageNumber))
    }
}
</script>