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
                    v-if="questionData"
                >
                    <label 
                        class="block text-gray-700 font-bold mb-2"
                    >Answer</label>

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
                    Since this question type has to be manually graded, no answer will be displayed in this preview.
                    Please click the "Cancel preview" button to finish.
                </div>

                <div class="flex w-full mt-4">
                    <button 
                        class="btn btn-blue btn-sm text-sm"
                        @click.prevent="submit"
                        v-if="!submitting"
                    >
                        Submit
                    </button>

                    <button 
                        class="btn btn-text btn-sm text-sm ml-auto"
                        @click.prevent="cancel"
                    >
                        Cancel preview
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

    watch: {
        imageSaved () {
            this.submitting = true
        }
    },

    methods: {
        async submit () {
            window.events.$emit('draw:save')
        },

        async cancel () {
            await axios.delete(`${this.urlBase}/uploads`, {
                data: {
                    files: [
                        {
                            file: this.form.answer.image
                        }
                    ]
                }
            })

            this.form.answer.text = ''
            this.form.answer.image = ''
            this.submitting = false
        }
    },

    async mounted () {
        window.events.$on('draw:saved', file => {
            this.form.answer.image = file

            this.imageSaved = true
        })
    }
}
</script>