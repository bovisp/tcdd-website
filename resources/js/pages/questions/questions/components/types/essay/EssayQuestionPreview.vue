<template>
    <div class="flex mx-auto flex-col w-2/3">
        <div class="flex w-full mb-6">
            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="$emit('question-preview:cancel')"
            >
                {{ trans('js_pages_questions_questions_components_types_essay_essayquestionpreview.cancelpreview') }}
            </button>
        </div>

        <h1 class="font-light text-4xl mb-4">
            {{ testQuestionData.name }}
        </h1>

        <template v-if="typeof parts !== 'undefined'">
            <component 
                v-for="part in orderBy(parts, ['sort_order'], ['asc'])"
                :key="part.id"
                :is="`Final${ ucfirst(part.builderType.type) }`"
                :part="part"
            ></component>
        </template>

        <div 
            class="mt-6"
            v-if="typeof testQuestionData.questionTypeData !== 'undefined'"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
            >{{ trans('js_pages_questions_questions_components_types_essay_essayquestionpreview.answer') }}</label>

            <template v-if="testQuestionData.questionTypeData.rich_text">
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
            {{ trans('js_pages_questions_questions_components_types_essay_essayquestionpreview.cancelpreviewtext') }}
        </div>

        <div class="flex w-full mt-4">
            <button 
                class="btn btn-blue btn-sm text-sm"
                @click.prevent="submitting = true"
                v-if="!submitting"
            >
                {{ trans('js_pages_questions_questions_components_types_essay_essayquestionpreview.cancelpreviewtext') }}
            </button>

            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="cancel"
            >
                {{ trans('js_pages_questions_questions_components_types_essay_essayquestionpreview.submit') }}
            </button>
        </div>
    </div>
</template>

<script>
import ucfirst from '../../../../../../helpers/ucfirst'
import { orderBy } from 'lodash-es'
import { mapActions, mapGetters } from 'vuex'
import { VueEditor, Quill } from 'vue2-editor'

export default {
    components: {
        VueEditor
    },

    props: {
        contentId: {
            type: Number,
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
        ...mapGetters({
            testQuestionData: 'questions/testQuestionData'
        })
    },

    methods: {
        ucfirst,

        orderBy,

        ...mapActions({
            fetchTestQuestionData: 'questions/fetchTestQuestionData'
        }),

        cancel () {
            this.form.answer.text = ''
            this.submitting = false
            
            this.$emit('question-preview:cancel')
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentId}`)

        this.parts = data.data.parts

        await this.fetchTestQuestionData()
    }
}
</script>