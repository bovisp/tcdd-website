<template>
    <div 
        class="w-full"
        v-if="question"
    >
        <div 
            v-if="duplicating"
        >
            <questions-duplicate />
        </div>

        <template v-else>
            <div 
                class="alert alert-red content mb-4"
                v-if="question.inAssessment"
            >
                <p>{{ trans('generic.questionaddedassessments') }}</p>

                <ul>
                    <li
                        v-for="assessment in question.assessments"
                        :key="assessment.assessment_id"
                    >
                        {{ assessment.assessment_name }}
                    </li>
                </ul>
            </div>
            
            <div
                :class="!previewing && !duplicating ? 'visible h-auto' : 'invisible h-0'"
                id="edit-pane"
            >
                <h1 class="text-3xl font-bold mb-4">
                    {{ trans('generic.edit') }}: {{ trans('generic.question') }} - {{ question.name }}
                </h1>

                <form 
                    @submit.prevent="update"
                >
                    <questions-edit-form 
                        :question="question"
                        @question:form-updated="updateForm"
                    />                

                    <hr class="my-6">

                    <div
                        class="w-full"
                    >
                        <button 
                            class="btn btn-blue text-sm"
                        >
                            {{ trans('js_pages_questions_questions_questionsedit.editquestion') }}
                        </button>

                        <button 
                            class="btn btn-text text-sm"
                            @click.prevent="cancel"
                        >
                            {{ trans('generic.cancel') }}
                        </button>

                        <button 
                            class="btn btn-text text-sm"
                            @click.prevent="preview"
                            v-scroll-to="'#preview-pane'"
                        >
                            {{ trans('generic.preview') }}
                        </button>

                        <button 
                            class="btn btn-text text-sm"
                            @click.prevent="duplicate"
                            v-scroll-to="'#duplicate-pane'"
                        >
                            {{ trans('generic.duplicate') }}
                        </button>
                    </div>
                </form>

                <hr>

                <destroy-question
                    v-if="hasRole(['administrator']) && !question.inAssessment"
                    @close="cancel"
                />
                
                <div 
                    class="alert alert-red content mt-4"
                    v-if="question.inAssessment"
                >
                    <p>{{ trans('generic.cannotdeletetext1') }}</p>

                    <p><strong>{{ trans('generic.assessments') }}:</strong></p>

                    <ul>
                        <li
                            v-for="assessment in question.assessments"
                            :key="assessment.assessment_id"
                        >
                            {{ assessment.assessment_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div 
                :class="previewing ? 'visible h-auto' : 'invisible h-0'"
                v-if="previewing && typeof question.type !== 'undefined'"
                id="preview-pane"
            >
                <component 
                    :is="`${pascalCase(question.type)}QuestionPreview`"
                    :id="question.contentBuilder[currentLang]"
                    @question-preview:cancel="cancelPreview"
                ></component>
            </div>
        </template>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ucfirst from '../../../helpers/ucfirst'
import { pascalCase, capitalCase } from 'change-case'

export default {
    data() {
        return {
            form: null,
            previewing: false,
            duplicating: false
        }
    },

    computed: {
        ...mapGetters({
            question: 'questions/question'
        })
    },

    watch: {
        previewing () {
            if (this.previewing === false) {
                window.events.$emit('content-builder:reload')
            }
        }
    },
    
    methods: {
        ucfirst,

        pascalCase,
        
        capitalCase,

        preview () {
            this.previewing = true
        },

        duplicate () {
            this.duplicating = true
        },

        cancelPreview () {
            this.previewing = false

            window.scrollTo(0,0)
        },

        cancelDuplicate () {
            this.duplicating = false

            window.scrollTo(0,0)
        },

        cancel () {
            window.events.$emit('questions:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.section_id = null
            this.form.question_category_id = null
            this.form.tags = []
            this.form.editors = []
            this.form.marking_guide_en = ''
            this.form.marking_guide_fr = ''
            this.form.question_type_data = {}
        },

        async update () {
           let { data } = await axios.put(`${this.urlBase}/api/questions/${this.question.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },

        updateForm (form) {
            this.form = form
        }
    }
}
</script>