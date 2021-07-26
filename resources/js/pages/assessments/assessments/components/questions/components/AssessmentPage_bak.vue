<template>
    <div id="drag-container">
        <hr class="border my-6">

        <h2 
            class="text-2xl font-light flex items-center"
            v-if="!updatePage"
        >
            <span v-if="currentPage" class="mr-1">
                {{ trans('generic.page') }} {{ currentPage.number }}
            </span> 
            
            <span v-if="currentPageScore">
                ({{ currentPageScore }} {{ trans('generic.points') }})
            </span>


            <b-button 
                class="has-text-danger ml-2"
                icon-right="delete"
                type="is-text"
                size="is-medium"
                :title="trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepage')"
                @click.prevent="confirmDestroy"
                v-if="!assessment.locked"
            />

            <b-button 
                icon-right="pencil"
                type="is-text"
                size="is-medium"
                :title="trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.updatepagenumber')"
                @click.prevent="updatePage = true"
                v-if="!assessment.locked"
            ></b-button>
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

        <div id="list-container">
            <div id="list">
                <assessment-page-content-list
                    v-for="data in orderBy(pageData.data, ['order'], ['asc'])"
                    :key="data.order"
                    :data="data"
                />
            </div>
        </div>
        <!-- <draggable
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
        </draggable> -->

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
import { filter, map, findIndex, orderBy } from 'lodash-es'
import autoScroll from 'dom-autoscroller'
import dragula from 'dragula'

export default {
    data () {
        return {
            type: '',
            modalActive: false,
            pageNumber: null,
            updatePage: false,
            questions: [],
            draggedQuestionObj: {
                moved: null,
                newOrderNumber: null,
                oldOrderNumber: null,
            },
            pageData: []
        }
    },

    watch: {
        currentPage: {
            deep: true,

            handler () {
                this.pageData = this.currentPage
            }
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

        orderBy,

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

            window.events.$emit('page:update', this.currentPage.number)
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

        async updateOrder () {
            await this.changeCurrentPageItemOrder(this.draggedQuestionObj)
            console.log(this.currentPage.data)
        },

        getQuestions () {
            return map(document.querySelectorAll('#list .question_id'), (question, key) => {
                return {
                    id: parseInt(question.value),
                    order: key += 1
                }
            })
        }
    },

    mounted () {
        window.events.$on('assessment-page:content-added', () => {
            this.type = ''
        })

        this.pageData = this.currentPage

        this.questions = this.getQuestions()

        var drake = dragula([document.querySelector('#list')]);

        drake.on('drag', (el, source) => {
            let questionId = parseInt(el.querySelector('.question_id').value)
            
            let oldPosition = findIndex(this.questions, ['id', questionId])

            this.draggedQuestionObj.moved = questionId
            this.draggedQuestionObj.oldOrderNumber = oldPosition + 1
        })

        drake.on('drop', async (el, target, source, sibling) => {
            let questionId = parseInt(el.querySelector('.question_id').value)

            this.questions = this.getQuestions()
            
            let newPosition = findIndex(this.questions, ['id', questionId])

            this.draggedQuestionObj.newOrderNumber = newPosition + 1

            await this.updateOrder()

            this.questions = this.getQuestions()
        })

        autoScroll([
                window,
                document.querySelector('#list-container')
            ],{
            margin: 20,
            autoScroll: function(){
                return this.down && drake.dragging;
            }
        });
    }
}
</script>