<template>
    <div class="flex items-center mt-3">
        <strong class="text-gray-700 mr-1">
            {{ trans('js_pages_questions_questions_components_types_questioneditpagenumber.pagenumber') }}:
        </strong>

        <template v-if="!editingPageNumber">
            {{ pageNumber }} 
            <button 
                class="btn btn-text text-sm btn-sm text-blue-500 ml-2"
                @click.prevent="editPageNumber"
                v-if="!assessment.locked"
            >{{ trans('js_pages_questions_questions_components_types_questioneditpagenumber.edit') }}</button>
        </template>

        <template v-if="editingPageNumber && !assessment.locked">
            <div class="relative">
                <select 
                    id="question_category_id"
                    v-model="number"
                    class="shadow appearance-none border rounded w-full py-1 pl-2 pr-5 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm"
                >
                    <option value=""></option>

                    <option
                        :value="p"
                        v-for="p in availablePages"
                        :key="p"
                        v-text="`${trans('js_pages_questions_questions_components_types_questioneditpagenumber.page')} ${p}`"
                    ></option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>

            <button 
                class="btn btn-blue text-sm btn-sm ml-2"
                @click.prevent="changePageNumber"
            >{{ trans('js_pages_questions_questions_components_types_questioneditpagenumber.change') }}</button>

            <button 
                class="btn btn-text text-sm btn-sm ml-2"
                @click.prevent="cancelEditPageNumber"
            >{{ trans('js_pages_questions_questions_components_types_questioneditpagenumber.cancel') }}</button>
        </template>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            editingPageNumber: false,
            number: null
        }
    },

    computed: {
        ...mapGetters({
            page: 'assessments/page',
            assessment: 'assessments/assessment'
        }),

        pageNumber () {
            return this.page.number
        },

        availablePages () {
            return Array.from({length: this.assessment.pages}, (_, i) => i + 1)
                .filter(p => p !== this.page.number)
        }
    },

    methods: {
        editPageNumber () {
            this.editingPageNumber = true

            this.number = this.page.number
        },

        async changePageNumber () {
            let assessmentPageContentItemId = this.question.model.assessment_page_content_items[0].id

            let { data } = await axios.patch(`${this.urlBase}/api/assessments/${this.assessment.id}/questions/${assessmentPageContentItemId}/change-page`, {
                page_number: this.number
            })

            window.events.$emit('assessment:question-page-change', this.number)

            this.cancelEditPageNumber()
        },

        cancelEditPageNumber () {
            this.editingPageNumber = false

            this.number = null
        },
    }
}
</script>