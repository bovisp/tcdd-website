<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Edit: Question - {{ question.name }}
        </h1>

        <p class="mb-4 text-gray-700">
            <strong>Owner:</strong> {{ question.author.fullname }}
        </p>

        <form 
            @submit.prevent="update"
        >
            <div
                class="w-full md:w-2/3 lg:w-1/2 mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.editors }"
                    for="editors"
                >
                    Editors
                </label>

                <multiselect 
                    v-model="form.editors" 
                    :options="availableEditors" 
                    :multiple="true" 
                    placeholder="Search editors"
                    label="fullname" 
                    track-by="id" 
                    :taggable="true"
                />

                <p
                    v-if="errors.editors"
                    v-text="errors.editors[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

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
                    :class="{ 'text-red-500': errors.description_en }"
                    for="description_en"
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
                    :class="{ 'text-red-500': errors.description_fr }"
                    for="description_fr"
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
                class="w-full md:w-1/3 lg:w-1/4 mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.score }"
                    for="score"
                >
                    Score
                </label>

                <input 
                    type="number" 
                    v-model="form.score"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="score"
                    :class="{ 'border-red-500': errors.score }"
                >

                <p
                    v-if="errors.score"
                    v-text="errors.score[0]"
                    class="text-red-500 text-sm"
                ></p>
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

            <hr class="my-6">

            <h3
                class="text-2xl font-light my-4"
                v-if="typeof questionTypeData.type !== 'undefined'"
            >
                {{ ucfirst(questionTypeData.type) }} Question Settings
            </h3>

            <template v-if="typeof questionTypeData.type !== 'undefined'">
                <component 
                    :is="`${questionTypeData.type}QuestionShow`"
                    @question-type:update-data="updateQuestionTypeData"
                ></component>
            </template>

            <hr class="my-6">

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Edit question
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </form>

        <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

        <destroy-question
            v-if="hasRole(['administrator'])"
            @close="cancel"
        />

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
                    <p>In order to add this tag, you will need to provide a {{ currentLang === 'en' ? 'French' : 'English' }} 
                    translation for the new tag '{{ tag }}'. If you do not have the translation, click the cancel button 
                    and you can always add it later by editing this new question.</p>

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
import { map } from'lodash-es'
import ucfirst from '../../../helpers/ucfirst'

export default {
    components: {
        Multiselect
    },

    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                score: null,
                section_id: '',
                question_category_id: null,
                marking_guide_en: '',
                marking_guide_fr: '',
                tags: [],
                editors: [],
                question_type_data: {}
            },
            modalAddTag: false,
            tag: '',
            tagTranslation: ''
        }
    },

    computed: {
        ...mapGetters({
            question: 'questions/question',
            sections: 'sections/sections',
            avalaibleTags: 'tags/tags',
            availableEditors: 'questions/availableEditors',
            questionCategories: 'questionCategories/questionCategories',
            questionTypeData: 'questions/questionTypeData'
        })
    },
    
    methods: {
        ucfirst,

        ...mapActions({
            fetchSections: 'sections/fetch',
            fetchTags: 'tags/fetch',
            fetchAvailableEditors: 'questions/fetchAvailableEditors',
            fetchQuestionCategories: 'questionCategories/fetch',
            setContentBuilderIds: 'questions/setContentBuilderIds',
            fetchQuestionTypeData: 'questions/fetchQuestionTypeData'
        }),

        cancel () {
            window.events.$emit('questions:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.score = null
            this.form.section_id = null
            this.form.question_category_id = null
            this.form.tags = []
            this.form.editors = []
            this.form.marking_guide_en = ''
            this.form.marking_guide_fr = ''
            this.form.question_type_data = {}
        },

        async update () {
            this.form.tags = await  Promise.all(map(this.form.tags, async (tag) => tag.id))

            this.form.editors = await  Promise.all(map(this.form.editors, async (editor) => editor.id))

            let { data } = await axios.put(`${this.urlBase}/api/questions/${this.question.id}`, this.form)

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
            this.form.question_type_data = data
        }
    },

    async mounted () {
        this.form.name_en = this.question.name_en
        this.form.name_fr = this.question.name_fr
        this.form.score = this.question.score
        this.form.section_id = this.question.section_id
        this.form.question_category_id = this.question.question_category_id
        this.form.tags = this.question.tags
        this.form.editors = this.question.editors
        this.form.marking_guide_en = this.question.marking_guide_en
        this.form.marking_guide_fr = this.question.marking_guide_fr

        await this.fetchSections()
        await this.fetchQuestionCategories()
        await this.fetchTags()
        await this.fetchAvailableEditors(this.question.id)
        await this.setContentBuilderIds(this.question.contentBuilder)
        await this.fetchQuestionTypeData(this.question.id)
    }
}
</script>