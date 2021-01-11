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
                        @click.prevent="submitting = true"
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

export default {
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

        questionData () {
            return this.question.items[0].question.question_data
        },

        totalPoints () {
            return this.question.model.assessment_page_content_items[0].question_score
        }
    },

    methods: {
        orderBy,

        pascalCase,

        cancel () {
            this.form.answer.text = ''
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
    }
}
</script>