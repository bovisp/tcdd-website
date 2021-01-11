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

                <div class="flex items-center">
                    <strong class="text-gray-700 mr-1">Points:</strong> 
                    
                    <template v-if="!editingScore">
                        {{ totalPoints }} 
                        <button 
                            class="btn btn-text text-sm btn-sm text-blue-500 ml-2"
                            @click.prevent="editScore"
                        >Edit</button>
                    </template>

                    <template v-else>
                        <input 
                            type="text"
                            class="shadow appearance-none border rounded w-32 py-1 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm"
                            v-model="score"
                        >

                        <button 
                            class="btn btn-blue text-sm btn-sm ml-2"
                            @click.prevent="changeScore"
                        >Change</button>

                        <button 
                            class="btn btn-text text-sm btn-sm ml-2"
                            @click.prevent="cancelEditScore"
                        >Cancel</button>
                    </template>
                </div>

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
import { find, orderBy } from 'lodash-es'
import { pascalCase } from 'change-case'
import unserialize from 'locutus/php/var/unserialize'
import { VueEditor, Quill } from 'vue2-editor'

export default {
    components: {
        VueEditor
    },

    props: {
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            parts: [],
            form: {
                answer: {
                    text: ''
                }
            },
            submitting: false,
            imageSaved: false,
            editingScore: false,
            score: null
        }
    },

    computed: {
        questionNumber () {
            return this.question.model.assessment_page_content_items[0].question_number
        },

        contentIdForLang () {
            return find(this.question.items[0].question.content_builder, builder => builder.language === this.currentLang)['id']
        },

        totalPoints () {
            return this.question.model.assessment_page_content_items[0].question_score
        },

        questionData () {
            return this.question.items[0].question.question_data
        },

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
        orderBy,

        pascalCase,

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
        },

        editScore () {
            this.editingScore = true

            this.score = this.question.model.assessment_page_content_items[0].question_score
        },

        cancelEditScore () {
            this.editingScore = false

            this.score = null
        },

        async changeScore () {
            let assessmentPageContentItemId = this.question.model.assessment_page_content_items[0].id

            let { data } = await axios.patch(`${this.urlBase}/api/assessments/questions/${assessmentPageContentItemId}/change-score`, {
                score: this.score
            })

            this.question.model.assessment_page_content_items[0].question_score = data

            this.cancelEditScore()
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentIdForLang}`)

        this.parts = data.data.parts

        window.events.$on('draw:saved', file => {
            this.form.answer.image = file

            this.imageSaved = true
        })
    }
}
</script>