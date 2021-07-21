<template>
    <div>
        <div>
            <strong>{{ trans('generic.totalscore') }}:</strong> {{ totalScore }}
        </div>

        <div class="flex items-end">
            <div 
                class="mr-auto"
                :class="{ 'mt-4' : !this.pages.length }"
            >
                <button 
                    class="btn btn-blue"
                    :class="{ 'btn-disabled': assessment.locked }"
                    @click.prevent="add"
                    :disabled="assessment.locked"
                >
                    <i class="fas fa-plus mr-1"></i>
                    {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpages.addpage') }}
                </button>
            </div>
            
            <div v-if="pages.length !== 0">
                <label 
                    for="content_type"
                    class="block text-gray-700 font-bold mb-2"
                >
                    {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpages.chooseapage') }}...
                </label>

                <div class="relative">
                    <select 
                        id="content_type"
                        v-model="page"
                        @input="setCurrentPage(this.page)"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    >
                        <option
                            :value="p.number"
                            v-for="p in pages"
                            :key="p.id"
                            v-text="`${trans('js_pages_assessments_assessments_components_questions_components_assessmentpages.page')} ${p.number}`"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="currentPage">
            <assessment-page />
        </template>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            page: null
        }
    },

    computed: {
        ...mapGetters({
            pages: 'assessments/pages',
            totalScore: 'assessments/totalScore',
            currentPage: 'assessments/currentPage',
            assessment: 'assessments/assessment'
        })
    },

    watch: {
        page () {
            this.setCurrentPage(this.page)
        },

        currentPage () {
            if (!this.currentPage) {
                this.page = null

                return
            }
            
            this.page = this.currentPage.number
        }
    },

    methods: {
        ...mapActions({
            add: 'assessments/addPage',
            setCurrentPage: 'assessments/setCurrentPage'
        })
    }
}
</script>