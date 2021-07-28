<template>
    <div class="flex my-4">
        <input type="hidden" :value="data.model.id" class="question_id">
        <div 
            class="w-2/12 flex items-start"
        >
            <i 
                class="fas fa-arrows-alt ml-auto cursor-move"
                v-if="!assessment.locked || (assessment.locked && data.type === 'ContentBuilder')"
            ></i>

            <i 
                v-if="data.type === 'Question'"
                class="fas fa-edit ml-2"
                :class="{ 'ml-auto':  assessment.locked }"
                :title="trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.editquestion')"
                @click.prevent="editQuestion"
            ></i>

            <i 
                class="fas fa-trash-alt text-red-500 ml-2"
                @click.prevent="confirmDestroy"
                :title="`${this.trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.delete')} ${data.type === 'ContentBuilder' ? this.trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.content') : this.trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.question')}`"
                v-if="!assessment.locked || (assessment.locked && data.type === 'ContentBuilder')"
            ></i>
        </div>

        <div
            class="w-10/12 pl-4"
        >
            <template v-if="data.type === 'ContentBuilder'">
                <div class="mb-6">
                    <assessment-page-content-builder 
                        v-for="item in orderBy(data.items, ['id'], ['asc'])"
                        :key="item.id"
                        :content-builder-id="item.id"
                        :lang="item.lang"
                    />
                </div>
            </template>

            <template v-if="data.type === 'Question'">
                <div class="mb-6">
                    <assessment-page-question 
                        :question="data"
                    />
                </div>
            </template>
        </div>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
            ok-button-text="Submit"
            cancel-button-text="Cancel"
        >
            <template slot="header">
                {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.deleteassessment') }} {{ data.type === 'ContentBuilder' ? trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.content') : trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.question') }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.deleteassessmentconfirm') }} {{ data.type === 'ContentBuilder' ? trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.content') : trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.question') }}?
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { orderBy } from 'lodash-es'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    data () {
        return {
            modalActive: false
        }
    },

    methods: {
        ...mapActions({
            deleteAssessmentPageItem: 'assessments/deleteAssessmentPageItem'
        }),

        orderBy,

        editQuestion () {
            let questionId = this.data.items[0].question.id

            window.location.href = `${this.urlBase}/questions?question=${questionId}`
        },

        confirmDestroy () {
            this.modalActive = true
        },

        close () {
            this.modalActive = false
        },

        async destroy () {
            this.close()
            
            await this.deleteAssessmentPageItem(this.data.model.id)

            window.events.$emit('page:item-delete')
        }
    }
}
</script>