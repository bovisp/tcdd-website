<template>
    <div>
        <hr class="border my-6">

        <h2 class="text-2xl font-light">
            <span v-if="currentPage">Page {{ currentPage.number }}</span> 
            
            <span v-if="currentPageScore">({{ currentPageScore }} points) </span>

            <i 
                class="fas fa-trash-alt text-red-500 ml-2"
                title="Delete page"
                @click.prevent="confirmDestroy"
            ></i>
        </h2>

        <!-- <assessment-questions-content-picker
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
                :page="currentPage"
            />
        </draggable> -->

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroyPage"
        >
            <template slot="header">
                Delete page
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        Are you sure you want to delete this page? 
                        All content and questions on this page will also be removed from the assessment. 
                        This operation cannot be undone.
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
// import { find, map, orderBy } from 'lodash-es'
// import Draggable from 'vuedraggable'

export default {
    computed: {
        ...mapGetters({
            currentPage: 'assessments/currentPage',
            currentPageScore: 'assessments/currentPageScore'
        })
    },
    // components: {
    //     Draggable
    // },

    data () {
        return {
            type: '',
            modalActive: false
        }
    },

    methods: {
        ...mapActions({
            destroy: 'assessments/destroyPage'
        }),

        close () {
            this.modalActive = false
        },

        confirmDestroy () {
            this.modalActive = true
        },

        async destroyPage () {
            this.modalActive = false

            this.destroy(this.currentPage.id)
        },

    //     setType (type) {
    //         this.type = type
    //     },

    //     update (e) {
    //         map(this.data, (d, index) => d.order = index + 1)

    //         axios.patch(`${this.urlBase}/api/assessment/page/${this.currentPage.id}/change-order`, {
    //             data: map(this.data, d => {
    //                 return {
    //                     id: d.model.id,
    //                     order: d.order
    //                 }
    //             })
    //         }).then(({data}) => {
    //             this.data = orderBy(data.data, ['order'], ['asc'])
    //         })
    //     },
    },

    // async mounted () {
    //     this.currentPage = find(this.pages, page => page.number === this.page)

    //     this.data = orderBy(this.currentPage.data, ['order'], ['asc'])

    //     for await (let pageItem of this.data) {
    //         if (pageItem.type === 'Question') {
    //             this.totalPagePoints += pageItem.model.assessment_page_content_items[0].question_score
    //         }
    //     }

    //     window.events.$on('assessment-page:change', page => {
    //         this.currentPage = find(this.pages, p => p.number === page)
    //     })

    //     window.events.$on('content-add:push', async (payload) => {
    //         await this.fetch()
    //     })

    //     window.events.$on('content:adding', adding => {
    //         this.adding = adding
    //     })
    // }
}
</script>