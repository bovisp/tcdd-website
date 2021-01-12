<template>
    <div v-if="currentPage">
        <hr class="border my-6">

        <h2 class="text-2xl font-light">
            Page {{ currentPage.number }} ({{ totalPagePoints }} points)
        </h2>

        <assessment-questions-content-picker
            @content:type="setType"
            v-if="!type"
        />

        <assessment-questions-content-add
            v-if="type"
            :type="type"
            @content-add:cancel="type = ''"
            :page="currentPage"
        />

        <draggable
            v-if="!adding"
            :list="data"
            handle='.fa-arrows-alt'
            @start="drag = true"
            @end="drag = false"
            @change="update"
        >
            <assessment-page-content-list
                v-for="d in data"
                :key="d.order"
                :data="d"
            />
        </draggable>
    </div>
</template>

<script>
import { find, map, orderBy } from 'lodash-es'
import Draggable from 'vuedraggable'

export default {
    components: {
        Draggable
    },

    props: {
        page: {
            type: Number,
            required: true
        },
        pages: {
            type: Array,
            required: true
        }
    },

    data () {
        return {
            type: '',
            currentPage: null,
            data: [],
            adding: false,
            totalPagePoints: 0
        }
    },

    watch: {
        currentPage: {
            deep: true,

            async handler () {
                this.currentPage = find(this.pages, page => page.number === this.page)

                await this.fetch()
            }
        }
    },

    methods: {
        setType (type) {
            this.type = type
        },

        update (e) {
            map(this.data, (d, index) => d.order = index + 1)

            axios.patch(`${this.urlBase}/api/assessment/page/${this.currentPage.id}/change-order`, {
                data: map(this.data, d => {
                    return {
                        id: d.model.id,
                        order: d.order
                    }
                })
            }).then(({data}) => {
                this.data = orderBy(data.data, ['order'], ['asc'])
            })
        },

        async fetch () {
            let { data } = await axios.get(`${this.urlBase}/api/assessment/page/${this.currentPage.id}`)

            this.data = orderBy(data.data, ['order'], ['asc'])

            this.totalPagePoints = 0

            for await (let pageItem of this.data) {
                if (pageItem.type === 'Question') {
                    this.totalPagePoints += pageItem.model.assessment_page_content_items[0].question_score
                }
            }
        }
    },

    async mounted () {
        this.currentPage = find(this.pages, page => page.number === this.page)

        this.data = orderBy(this.currentPage.data, ['order'], ['asc'])

        for await (let pageItem of this.data) {
            if (pageItem.type === 'Question') {
                this.totalPagePoints += pageItem.model.assessment_page_content_items[0].question_score
            }
        }

        window.events.$on('assessment-page:change', page => {
            this.currentPage = find(this.pages, p => p.number === page)
        })

        window.events.$on('content-add:push', async (payload) => {
            await this.fetch()
        })

        window.events.$on('assessment-pages:reload', async () => {
            await this.fetch()
        })

        window.events.$on('content:adding', adding => {
            this.adding = adding
        })
    }
}
</script>