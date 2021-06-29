<template>
    <div v-if="!assessment.locked">
        <template v-if="!showQuestionSettings">
            <datatable 
                v-if="availableQuestions && availableQuestions.length"
                :data="availableQuestions"
                :columns="columns"
                :per-page="10"
                :order-keys="['categoryName', 'name']"
                :order-key-directions="['asc', 'asc']"
                :has-event="true"
                :event-text="trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionsadd.add')"
                event="questions:add"
            />

            <div 
                class="alert alert-blue"
                v-else
            >
                {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionsadd.noquestions') }}
            </div>
        </template>

        <template v-if="showQuestionSettings">
            <hr class="my-8">

            <div v-if="!scoreLogged">
                <div
                    class="mb-4"
                >
                    <label
                        class="block text-gray-700 font-bold mb-2" 
                        for="score"
                    >
                        {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionsadd.questionscore') }}
                    </label>

                    <input 
                        type="number" 
                        v-model="score"
                        class="shadow appearance-none border rounded w-full lg:w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                        id="title"
                    >
                </div>

                <div>
                    <button 
                        class="btn btn-blue btn-sm text-sm"
                        @click.prevent="add"
                    >
                        {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionsadd.addscore') }}
                    </button>
                </div>
            </div>

            <div v-else>
                {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionsadd.pleaseclick') }}
            </div>

            <hr class="my-8">
        </template>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            columns: [
                { field: 'categoryName', title: this.trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionsadd.category'), sortable: true },
                { field: 'name', title: this.trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionsadd.name'), sortable: true }
            ],
            showQuestionSettings: false,
            score: null,
            question: null,
            scoreLogged: false
        }
    },

    computed: {
        ...mapGetters({
            currentPage: 'assessments/currentPage',
            availableQuestions: 'assessments/availableQuestions',
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            fetchAvailableQuestions: 'assessments/fetchAvailableQuestions',
            addQuestionToPage: 'assessments/addQuestionToPage'
        }),

        add () {
            this.scoreLogged = true

            window.events.$emit('assessment-page:content-added')

            this.addQuestionToPage({
                question: this.question,
                score: this.score
            })
        }
    },

    async mounted () {
        await this.fetchAvailableQuestions(this.currentPage.assessment_id)

        window.events.$on('questions:add', item => {
            this.showQuestionSettings = true

            this.question = item
        })
    }
}
</script>