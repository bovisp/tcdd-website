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

                <strong class="text-gray-700">Points:</strong> {{ totalPoints }}

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
            submitting: false
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
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentIdForLang}`)

        this.parts = data.data.parts
    }
}
</script>