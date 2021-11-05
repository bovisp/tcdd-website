<template>
    <div class="flex w-full">
        <div class="w-2/12 flex flex-col xl:flex-row items-end xl:items-start xl:justify-end">
            <b-icon
                icon="arrow-all"
                class="drag-item mt-3"
                style="cursor: move;"
                v-if="!assessment.locked"
            ></b-icon>

            <b-button
                icon-right="pencil"
                type="is-text"
                size="is-medium"
                v-if="data.type === 'Question'"
                :title="`${trans('generic.edit')} ${noCase(trans('generic.question'))}`"
                @click.prevent="questionEditModal = true" 
            ></b-button>

            <b-button
                icon-right="delete"
                type="is-text"
                size="is-medium"
                :title="`${trans('generic.delete')} ${noCase(data.type === 'ContentBuilder' ? trans('generic.content') : trans('generic.question'))}`"
                class="has-text-danger"
                :disabled="assessment.locked"
                @click.prevent="$buefy.dialog.confirm({
                    title: `${trans('generic.delete')} ${noCase(trans('generic.question'))}`,
                    message: `${trans('js_pages_assessments_assessments_components_questions_components_assessmentpageitem.deleteassessmentconfirm')} ${data.type === 'ContentBuilder' ? trans('generic.content') : trans('generic.question')}?`,
                    confirmText: `${trans('generic.delete')} ${noCase(trans('generic.question'))}`,
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => destroy()
                })" 
            ></b-button>
        </div>

        <div class="w-10/12">
            <template v-if="data.type === 'ContentBuilder'">
                <div class="mb-6">
                    <b-field
                        v-for="item in orderBy(data.items, ['id'], ['asc'])"
                        :key="item.id"
                    >
                        <content-builder 
                            :id="item.id"
                        />
                    </b-field>
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
        
        <b-modal
            v-model="questionEditModal"
            has-modal-card
            aria-role="dialog"
            :aria-label="`${trans('generic.edit')} ${noCase(trans('generic.question'))}`"
            aria-modal
        >
            <form action="">
                <div class="modal-card" style="width: auto">
                    <header class="modal-card-head">
                        <p class="modal-card-title">
                            {{ trans('generic.edit') }} {{ noCase(trans('generic.question')) }}
                        </p>

                        <button
                            type="button"
                            class="delete"
                            @click="cancel"
                        />
                    </header>

                    <section 
                        class="modal-card-body"
                        v-if="question"
                    >
                        <questions-edit-form 
                            :question="question"
                            @question:form-updated="updateForm"
                        />
                    </section>

                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click.prevent="cancel" 
                        />

                        <b-button
                            :label="`${trans('generic.edit')} ${noCase(trans('generic.question'))}`"
                            type="is-primary"
                            @click.prevent="updateQuestion" 
                        />
                    </footer>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
import { noCase } from 'change-case'
import { mapActions, mapMutations, mapGetters } from 'vuex'
import { orderBy } from 'lodash-es'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            questionEditModal: false,
            form: null
        }
    },

    computed: {
        ...mapGetters({
            question: 'questions/question',
            assessment: 'assessments/assessment'
        })
    },

    watch: {
        async questionEditModal () {
            if (this.questionEditModal === true) {
                await this.fetchQuestion(this.data.items[0].question.id)
                await this.fetchAvailableEditors(this.question.id)
                await this.fetchSections()
                await this.fetchQuestionCategories()
                await this.fetchTags()
                await this.fetchQuestionTypeData(this.question.id)
            }
        }
    },

    methods: {
        ...mapActions({
            deleteAssessmentPageItem: 'assessments/deleteAssessmentPageItem',
            fetchQuestion: 'questions/setEdit',
            fetchAvailableEditors: 'questions/fetchAvailableEditors',
            fetchSections: 'sections/fetch',
            fetchQuestionCategories: 'questionCategories/fetch',
            fetchTags: 'tags/fetch',
            fetchQuestionTypeData: 'questions/fetchQuestionTypeData',
        }),

        ...mapMutations({
            clearQuestion: 'questions/SET_QUESTION'
        }),

        noCase,

        orderBy,

        async destroy () {
            await this.deleteAssessmentPageItem(this.data.model.id)

            window.events.$emit('page:item-delete')

            this.$buefy.toast.open({
                message: 'Question successfully deleted.',
                type: 'is-success'
            })
        },

        async updateQuestion () {
           let { data } = await axios.put(`${this.urlBase}/api/questions/${this.question.id}`, this.form)

            this.cancel()

            window.events.$emit('page:question-edit')

            this.$toasted.success(data.data.message)
        },

        cancel () {
            this.questionEditModal = false

            this.clearQuestion({})
        },

        updateForm (form) {
            this.form = form
        }
    }
}
</script>