<template>
    <div>
        <hr class="border my-6">

        <h2 
            class="text-2xl font-light"
            v-if="!updatePage"
        >
            <span v-if="currentPage">
                {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.page') }} {{ currentPage.number }}
            </span> 
            
            <span v-if="currentPageScore">
                ({{ currentPageScore }} {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.points') }})
            </span>

            <i 
                class="fas fa-trash-alt text-red-500 ml-2"
                :title="trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepage')"
                @click.prevent="confirmDestroy"
                v-if="!assessment.locked"
            ></i>

            <i 
                class="fas fa-edit ml-2"
                :title="trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.updatepagenumber')"
                @click.prevent="updatePage = true"
                v-if="!assessment.locked"
            ></i>
        </h2>

        <div
            class="w-full lg:w-1/3"
            v-if="updatePage && !assessment.locked"
        >
            <label 
                for="page_number"
                class="block text-gray-700 font-bold mb-2"
            >
                {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.changepagenumber') }}
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
                        v-text="`${trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.page')} ${page.number}`"
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
                    {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.cancel') }}
                </button>

                <button 
                    class="btn btn-sm btn-blue text-sm ml-2"
                    @click.prevent="update"
                >
                    {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.change') }}
                </button>
            </div>
        </div>

        <assessment-questions-content-picker
            @content:type="setType"
            v-if="!type"
        />

        <assessment-questions-content-add
            v-if="type"
            :type="type"
            @content-add:cancel="type = ''"
        />

        <draggable
            v-if="!type"
            :list="currentPage.data"
            handle='.fa-arrows-alt'
            @start="drag = true"
            @end="drag = false"
            @change="updateOrder"
        >
            <assessment-page-content-list
                v-for="data in currentPage.data"
                :key="data.order"
                :data="data"
            />
        </draggable>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroyPage"
            ok-button-text="Submit"
            cancel-button-text="Cancel"
        >
            <template slot="header">
                {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepage') }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepageconfirm') }}
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { filter } from 'lodash-es'
import Draggable from 'vuedraggable'

export default {
    components: {
        Draggable
    },

    data () {
        return {
            type: '',
            modalActive: false,
            pageNumber: null,
            updatePage: false
        }
    },

    computed: {
        ...mapGetters({
            currentPage: 'assessments/currentPage',
            currentPageScore: 'assessments/currentPageScore',
            pages: 'assessments/pages',
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        filter,

        ...mapActions({
            destroy: 'assessments/destroyPage',
            updatePageNumber: 'assessments/updatePageNumber',
            fetchPages: 'assessments/fetchPages',
            changeCurrentPageItemOrder: 'assessments/changeCurrentPageItemOrder'
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

        setType (type) {
            this.type = type
        },

        async updateOrder (e) {
            await this.changeCurrentPageItemOrder({
                moved: e.moved.element.model.id,
                newOrderNumber: e.moved.newIndex + 1,
                oldOrderNumber: e.moved.oldIndex + 1,
            })
        }
        
    },

    mounted () {
        window.events.$on('assessment-page:content-added', () => {
            this.type = ''
        })
    }
}
</script>