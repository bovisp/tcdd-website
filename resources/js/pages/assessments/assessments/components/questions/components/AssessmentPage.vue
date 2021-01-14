<template>
    <div>
        <hr class="border my-6">

        <h2 
            class="text-2xl font-light"
            v-if="!updatePage"
        >
            <span v-if="currentPage">Page {{ currentPage.number }}</span> 
            
            <span v-if="currentPageScore">({{ currentPageScore }} points) </span>

            <i 
                class="fas fa-trash-alt text-red-500 ml-2"
                title="Delete page"
                @click.prevent="confirmDestroy"
            ></i>

            <i 
                class="fas fa-edit ml-2"
                title="Update page number"
                @click.prevent="updatePage = true"
            ></i>
        </h2>

        <div
            class="w-full lg:w-1/3"
            v-else
        >
            <label 
                for="page_number"
                class="block text-gray-700 font-bold mb-2"
            >
                Change page number
            </label>

            <div class="relative">
                <select 
                    id="section_id"
                    v-model="pageNumber"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    :class="{ 'border-red-500': errors.page_number }"
                >
                    <option
                        :value="page.number"
                        v-for="page in filter(pages, page => page.number !== currentPage.number)"
                        :key="page.id"
                        v-text="`Page ${page.number}`"
                    ></option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>

            <div class="flex justify-end mt-2">
                <button 
                    class="btn btn-sm btn-text text-sm"
                    @click.prevent="updatePage = false"
                >
                    Cancel
                </button>

                <button 
                    class="btn btn-sm btn-blue text-sm ml-2"
                    @click.prevent="update"
                >
                    Change
                </button>
            </div>
        </div>

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
import { filter } from 'lodash-es'
// import Draggable from 'vuedraggable'

export default {
    computed: {
        ...mapGetters({
            currentPage: 'assessments/currentPage',
            currentPageScore: 'assessments/currentPageScore',
            pages: 'assessments/pages'
        })
    },
    // components: {
    //     Draggable
    // },

    data () {
        return {
            type: '',
            modalActive: false,
            pageNumber: null,
            updatePage: false
        }
    },

    methods: {
        filter,

        ...mapActions({
            destroy: 'assessments/destroyPage',
            updatePageNumber: 'assessments/updatePageNumber'
        }),

        async update () {
            await this.updatePageNumber({
                newPageNumber: this.pageNumber,
                oldPageNumber: this.currentPage.number
            })

            this.pageNumber = null
            this.updatePage = false
        },

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
    }
}
</script>