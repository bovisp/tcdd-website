<template>
    <div class="flex mx-auto flex-col w-2/3">
        <div class="flex w-full mb-6">
            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="$emit('question-preview:cancel')"
            >
                {{ trans('generic.cancelpreview') }}
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
                :id="id"
                :data="part"
            ></component>
        </template>

        <div 
            class="mt-6"
            v-if="typeof testQuestionData.questionTypeData !== 'undefined'"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
            >{{ trans('generic.answer') }}</label>

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

        <b-message 
            type="is-info"
            v-if="submitting"
            class="mt-4"
        >
            {{ trans('js_pages_questions_questions_components_types_essay_essayquestionpreview.cancelpreviewtext') }}
        </b-message>

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
        id: {
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
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.id}`)

        this.parts = data.data.parts

        await this.fetchTestQuestionData()
    }
}
</script>