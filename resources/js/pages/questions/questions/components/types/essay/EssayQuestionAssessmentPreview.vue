<template>
    <div
        v-if="question"
    >
        <div
            class="flex"
        >
            <div class="mr-2">
                {{ questionNumber }}.
            </div>

            <div 
                class="flex-1 -mt-4"
                v-if="typeof parts !== 'undefined'"
            >
                <component 
                    v-for="part in orderBy(parts, ['sort_order'], ['asc'])"
                    :key="part.id"
                    :is="`Final${ pascalCase(part.builderType.type) }`"
                    :data="part"
                    :id="contentIdForLang"
                ></component>

                <question-edit-score 
                    :question="question"
                    @question:update-score="updateScore"
                />

                <question-edit-page-number 
                    :question="question"
                />

                <div 
                    class="mt-6"
                >
                    <label 
                        class="block text-gray-700 font-bold mb-2"
                    >{{ trans('generic.answer') }}</label>

                    <template v-if="questionData.rich_text">
                        <vue-editor 
                            v-model="form.answer.text"
                        ></vue-editor>
                    </template>

                    <template v-else>
                        <textarea 
                            class="form-textarea mt-1 block w-full" 
                            rows="10" 
                            v-model="form.answer.text"></textarea>
                    </template>
                </div>

                <div 
                    class="alert alert-blue mt-4"
                    v-if="submitting"
                >
                    {{ trans('js_pages_questions_questions_components_types_essay_essayquestionassessmentpreview.cancelpreviewtext') }}
                </div>

                <div class="flex w-full mt-4">
                    <button 
                        class="btn btn-blue btn-sm text-sm"
                        @click.prevent="submitting = true"
                        v-if="!submitting"
                    >
                        {{ trans('generic.submit') }}
                    </button>

                    <button 
                        class="btn btn-text btn-sm text-sm ml-auto"
                        @click.prevent="cancel"
                    >
                        {{ trans('generic.cancelpreview') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import questionAssessmentPreview from '../../../../../../mixins/questionAssessmentPreview'

export default {
    mixins: [
        questionAssessmentPreview
    ],

    data () {
        return {
            form: {
                answer: {
                    text: ''
                }
            }
        }
    },

    methods: {
        cancel () {
            this.form.answer.text = ''
            this.submitting = false
        }
    }
}
</script>