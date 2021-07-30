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
                    :part="part"
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
                    <draw-component
                        v-if="questionData"
                        :background-image="drawingOptions.background_image[0].file"
                        :pen-colors="drawingOptions.pen_colors"
                    />
                </div>

                <div 
                    class="mt-6"
                    v-if="questionData.text_answer"
                >
                    <label 
                        class="block text-gray-700 font-bold mb-2"
                    >{{ trans('js_pages_questions_components_types_drawing_drawingquestionassessmentpreview.answer') }}</label>

                    
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
                    {{ trans('js_pages_questions_components_types_drawing_drawingquestionassessmentpreview.cancelpreviewmessage') }}
                </div>

                <div class="flex w-full mt-4">
                    <button 
                        class="btn btn-blue btn-sm text-sm"
                        @click.prevent="submit"
                        v-if="!submitting"
                    >
                        {{ trans('js_pages_questions_components_types_drawing_drawingquestionassessmentpreview.submit') }}
                    </button>

                    <button 
                        class="btn btn-text btn-sm text-sm ml-auto"
                        @click.prevent="cancel"
                    >
                        {{ trans('js_pages_questions_components_types_drawing_drawingquestionassessmentpreview.cancelpreview') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import unserialize from 'locutus/php/var/unserialize'
import { VueEditor, Quill } from 'vue2-editor'
import questionAssessmentPreview from '../../../../../../mixins/questionAssessmentPreview'

export default {
    mixins: [
        questionAssessmentPreview
    ],

    components: {
        VueEditor
    },

    data () {
        return {
            form: {
                answer: {
                    text: ''
                }
            },
            imageSaved: false
        }
    },

    computed: {
        drawingOptions () {
            return unserialize(this.question.items[0].question.question_data.drawing_options)
        }
    },

    methods: {
        async submit () {
            this.submitting = true
        },

        async cancel () {
            this.form.answer.text = ''
            this.form.answer.image = ''
            this.submitting = false

            window.events.$emit('draw:clear-preview')
        }
    }
}
</script>