<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('generic.duplicate') }}: {{ trans('generic.question') }} - {{ question.name }}
        </h1>

        <form 
            @submit.prevent="store"
        >
            <div
                class="w-full md:w-2/3 lg:w-1/2 mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.editors }"
                    for="editors"
                >
                    {{ trans('generic.editors') }}
                </label>

                <multiselect 
                    v-model="form.editors" 
                    :options="availableEditors" 
                    :multiple="true" 
                    :placeholder="trans('js_pages_questions_questions_questionsduplicate.searcheditors')"
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
                    {{ trans('generic.section') }}
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
                    {{ trans('generic.questioncategory') }}
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
                    {{ trans('generic.nameenglish') }}
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
                    {{ trans('generic.namefrench') }}
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
                    {{ trans('generic.questiontextenglish') }}
                </label>

                <template v-if="contentIdsChanged || tempIdChanged">
                    <content-builder 
                        :id="tempId.contentBuilder.en"
                    />
                </template>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.description_fr }"
                    for="description_fr"
                >
                    {{ trans('generic.questiontextfrench') }}
                </label>

                <template v-if="contentIdsChanged || tempIdChanged">
                    <content-builder 
                        :id="tempId.contentBuilder.fr"
                    />
                </template>
            </div>

            <div class="mb-4">
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.marking_guide_en }"
                >
                    {{ trans('generic.markingguideenglish') }}
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
                    {{ trans('generic.markingguidefrench') }}
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
                    {{ trans('generic.tags') }}
                </label>

                <multiselect 
                    v-model="form.tags" 
                    :options="avalaibleTags" 
                    :multiple="true" 
                    :placeholder="trans('generic.searchaddtag')" 
                    :tag-placeholder="trans('generic.addasnewtag')"
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
                {{ capitalCase(questionTypeData.type) }} {{ trans('generic.questionsettings') }}
            </h3>

            <component 
                :is="`${pascalCase(questionTypeData.type)}QuestionEdit`"
                @question-type:update-data="updateQuestionTypeData"
            ></component>

            <hr class="my-6">

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    {{ trans('js_pages_questions_questions_questionsduplicate.createquestion') }}
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    {{ trans('generic.cancel') }}
                </button>
            </div>
        </form>

        <modal 
            v-show="modalAddTag"
            @close="close"
            @submit="storeTag"
            ok-button-text="Submit"
            cancel-button-text="Cancel"
        >
            <template slot="header">
                {{ trans('generic.add') }} {{ currentLang === 'en' ? trans('generic.french') : trans('generic.english') }} {{ trans('generic.translationtag') }}: {{ tag }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p>{{ trans('generic.newtagmessage1') }} {{ currentLang === 'en' ? trans('generic.french') : trans('generic.english') }} 
                    {{ trans('generic.newtagmessage2') }} '{{ tag }}'. {{ trans('generic.newtagmessage3') }}</p>

                    <div
                        class="w-full mb-4"
                    >
                        <label 
                            class="block text-gray-700 font-bold mb-2" 
                            :class="{ 'text-red-500': errors.name_en || errors.name_fr }"
                            for="name"
                        >
                            {{ currentLang === 'en' ? trans('generic.french') : trans('generic.english') }} {{ trans('generic.translationfor') }} '{{ tag }}'
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
import { mapActions, mapGetters } from 'vuex'
import Multiselect from 'vue-multiselect'
import ucfirst from '../../../helpers/ucfirst'
import { capitalCase, pascalCase } from 'change-case'

export default {
    components: {
        Multiselect
    },

    data () {
        return {
            form: {
                name_en: '',
                name_fr: '',
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
            tagTranslation: '',
            contentIdsChanged: false,
            tempIdChanged: false
        }
    },

    computed: {
        ...mapGetters({
            question: 'questions/question',
            availableEditors: 'questions/availableEditors',
            avalaibleTags: 'tags/tags',
            sections: 'sections/sections',
            questionCategories: 'questionCategories/questionCategories',
            questionTypeData: 'questions/questionTypeData',
            tempId: 'questions/tempId',
        })
    },

    watch: {
        tempId: {
            deep: true,

            handler () {
                this.tempIdChanged = true
                this.contentIdsChanged = true
            }
        }
    },

    methods: {
        ucfirst,

        capitalCase,
        
        pascalCase,

        ...mapActions({
            duplicateQuestion: 'questions/duplicateQuestion',
            fetchAvailableEditors: 'questions/fetchAvailableEditors',
            fetchTags: 'tags/fetch'
        }),
        
        async store () {
            await axios.put(`${this.urlBase}/api/questions/${this.question.id}`, this.form)

            window.location.href = `${this.urlBase}/questions`
        },

        async cancel () {
            await axios.delete(`${this.urlBase}/api/questions/${this.question.id}`)

            window.location.href = `${this.urlBase}/questions`
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
        await this.duplicateQuestion()
        await this.fetchAvailableEditors(this.question.id)

        this.form.name_en = this.question.name_en
        this.form.name_fr = this.question.name_fr
        this.form.section_id = this.question.section_id
        this.form.question_category_id = this.question.question_category_id
        this.form.tags = this.question.tags
        this.form.editors = this.question.editors
        this.form.marking_guide_en = this.question.marking_guide_en
        this.form.marking_guide_fr = this.question.marking_guide_fr
    }
}
</script>