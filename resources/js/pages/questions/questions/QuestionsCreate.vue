<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            New question
        </h1>

        <form 
            @submit.prevent="store"
        >
            <div
                class="w-full lg:w-1/3 mb-4"
            >
                <label 
                    for="section_id"
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.section_id }"
                >
                    Section
                </label>

                <div class="relative">
                    <select 
                        id="section_id"
                        v-model="form.section_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.section_id }"
                    >
                        <option value=""></option>

                        <option
                            :value="section.id"
                            v-for="section in sections"
                            :key="section.id"
                            v-text="section.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.section_id"
                    v-text="errors.section_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full lg:w-1/3 mb-4"
            >
                <label 
                    for="question_category_id"
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.question_category_id }"
                >
                    Question category
                </label>

                <div class="relative">
                    <select 
                        id="question_category_id"
                        v-model="form.question_category_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.question_category_id }"
                    >
                        <option value=""></option>

                        <option
                            :value="questionCategory.id"
                            v-for="questionCategory in questionCategories"
                            :key="questionCategory.id"
                            v-text="questionCategory.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.question_category_id"
                    v-text="errors.question_category_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name_en }"
                    for="name_en"
                >
                    Name (English)
                </label>

                <input 
                    type="text" 
                    v-model="form.name_en"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="name_en"
                    :class="{ 'border-red-500': errors.name_en }"
                >

                <p
                    v-if="errors.name_en"
                    v-text="errors.name_en[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name_fr }"
                    for="name_fr"
                >
                    Name (French)
                </label>

                <input 
                    type="text" 
                    v-model="form.name_fr"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="name_fr"
                    :class="{ 'border-red-500': errors.name_fr }"
                >

                <p
                    v-if="errors.name_fr"
                    v-text="errors.name_fr[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                >
                    Question text (English)
                </label>

                <content-builder 
                    lang="en"
                />
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                >
                    Question text (French)
                </label>

                <content-builder 
                    lang="fr"
                />
            </div>

            <div class="mb-4">
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.marking_guide_en }"
                >
                    Marking guide (English)
                </label>

                <vue-editor 
                    v-model="form.marking_guide_en"
                ></vue-editor>

                <div 
                    class="mt-1 text-red-500 text-xs"
                    v-if="errors.marking_guide_en"
                    v-text="errors.marking_guide_en[0]"
                ></div>
            </div>

            <div class="mb-4">
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.marking_guide_fr }"
                >
                    Marking guide (French)
                </label>

                <vue-editor 
                    v-model="form.marking_guide_fr"
                ></vue-editor>

                <div 
                    class="mt-1 text-red-500 text-xs"
                    v-if="errors.marking_guide_fr"
                    v-text="errors.marking_guide_fr[0]"
                ></div>
            </div>

            <div
                class="w-full md:w-2/3 lg:w-1/2 mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.tags }"
                    for="tags"
                >
                    Tags
                </label>

                <multiselect 
                    v-model="form.tags" 
                    :options="avalaibleTags" 
                    :multiple="true" 
                    placeholder="Search or add a tag" 
                    tag-placeholder="Add this as new tag"
                    label="name" 
                    track-by="id" 
                    :taggable="true"
                    @tag="tagModal"
                />

                <p
                    v-if="errors.tags"
                    v-text="errors.tags[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full lg:w-1/3 mb-4"
            >
                <label 
                    for="question_type_id"
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.question_type_id }"
                >
                    Question type
                </label>

                <div class="relative">
                    <select 
                        id="question_type_id"
                        v-model="form.question_type_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.question_type_id }"
                    >
                        <option value=""></option>

                        <option
                            :value="questionType.id"
                            v-for="questionType in questionTypes"
                            :key="questionType.id"
                            v-text="questionType.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.question_type_id"
                    v-text="errors.question_type_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <template v-if="type">
                <hr class="my-6">

                <h3
                    class="text-2xl font-light my-4"
                >
                    {{ capitalCase(type) }} Question Settings
                </h3>

                <component 
                    :is="`${pascalCase(type)}QuestionCreate`"
                    @question-type:update-data="updateQuestionTypeData"
                ></component>

                <hr class="my-6">
            </template>

            <div 
                class="mb-4"
                v-if="noQuestionType"
            >
                <div class="alert alert-red">
                    You need to associate this question with a question type.
                </div>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Add question
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="remove"
                >
                    Cancel
                </button>
            </div>
        </form>

        <modal 
            v-show="modalAddTag"
            @close="close"
            @submit="storeTag"
        >
            <template slot="header">
                Add {{ currentLang === 'en' ? 'French' : 'English' }} translation of new tag: {{ tag }}
            </template>

            <template slot="body">
                <div class="my-4">
                    In order to add this tag, you will need to provide a {{ currentLang === 'en' ? 'French' : 'English' }} 
                    translation for the new tag '{{ tag }}'. If you do not have the translation, click the cancel button 
                    and you can always add it later by editing this new question.

                    <div
                        class="w-full mb-4"
                    >
                        <label 
                            class="block text-gray-700 font-bold mb-2" 
                            :class="{ 'text-red-500': errors.name_en || errors.name_fr }"
                            for="name"
                        >
                            {{ currentLang === 'en' ? 'French' : 'English' }} translation for '{{ tag }}'
                        </label>

                        <input 
                            type="text" 
                            v-model="tagTranslation"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                            id="name"
                            :class="{ 'border-red-500': errors.name_en || errors.name_fr }"
                        >

                        <p
                            v-if="errors.name_en || errors.name_fr"
                            v-text="currentLang === 'en' ? errors.name_en[0] : errors.name_fr[0]"
                            class="text-red-500 text-sm"
                        ></p>
                    </div>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Multiselect from 'vue-multiselect'
import { VueEditor, Quill } from 'vue2-editor'
import { map, find } from 'lodash-es'
import ucfirst from '../../../helpers/ucfirst'
import { capitalCase, pascalCase } from 'change-case'

export default {
    components: {
        Multiselect,
        VueEditor
    },

    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                marking_guide_fr: '',
                marking_guide_en: '',
                section_id: null,
                question_category_id: null,
                tags: [],
                id: null,
                question_type_id: null,
                question_type_data: {}
            },
            modalAddTag: false,
            tag: '',
            tagTranslation: '',
            type: '',
            noQuestionType: false
        }
    },

    computed: {
        ...mapGetters({
            sections: 'sections/sections',
            avalaibleTags: 'tags/tags',
            questionCategories: 'questionCategories/questionCategories',
            questionId: 'questions/tempId',
            questionTypes: 'questionTypes/questionTypes',
        })
    },

    watch: {
        'form.question_type_id' () {
            this.type = find(this.questionTypes, type => {
                return type.id === this.form.question_type_id
            })['code']
        }
    },

    methods: {
        capitalCase,

        pascalCase,

        ucfirst,

        ...mapActions({
            fetchSections: 'sections/fetch',
            fetchTags: 'tags/fetch',
            fetchQuestionCategories: 'questionCategories/fetch',
            createTempQuestionId: 'questions/createId',
            removeTempIds: 'questions/removeTempIds',
            fetchQuestionTypes: 'questionTypes/fetch',
        }),

        async remove () {
            let { data } = await axios.delete(`${this.urlBase}/api/questions/${this.questionId}`)

            this.cancel()
        },

        async cancel () {
            window.events.$emit('questions:create-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.section_id = null
            this.form.question_category_id = null
            this.form.tags = []
            this.form.marking_guide_fr = ''
            this.form.marking_guide_en = ''
            this.question_type_id = null
            this.type = ''

            await this.removeTempIds(this.questionId)
        },

        async store () {
            if (!this.type) {
                this.noQuestionType = true

                return
            }

            this.noQuestionType = false

            let { data } = await axios.post(`${this.urlBase}/api/questions`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },

        tagModal (tag) {
            this.tag = tag

            this.modalAddTag = true
        },

        close () {
            this.tag = ''
            this.tagTranslation = ''

            this.$store.dispatch('clearErrors')

            this.modalAddTag = false
        },

        async storeTag () {
            let { data } = await axios.post(`${this.urlBase}/api/admin/tags`, {
                name_en: currentLang === 'en' ? this.tag : this.tagTranslation,
                name_fr: currentLang === 'en' ? this.tagTranslation : this.tag
            })

            await this.fetchTags()

            await this.form.tags.push(data.data.tag)

            this.close()

            this.$toasted.success(data.data.message)
        },

        updateQuestionTypeData (data) {
            this.noQuestionType = false
            
            this.form.question_type_data = data
        }
    },

    async mounted () {
        await this.createTempQuestionId()
        await this.fetchSections()
        await this.fetchTags()
        await this.fetchQuestionCategories()
        await this.fetchQuestionTypes()

        this.form.id = this.questionId

        window.events.$on('questions:temporary-id', questionId => this.questionId = questionId)
    }
}
</script>