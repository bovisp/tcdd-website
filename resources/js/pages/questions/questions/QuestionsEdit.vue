<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Edit: Question - {{ question.name }}
        </h1>

        <form 
            @submit.prevent="update"
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
                    :class="{ 'text-red-500': errors.description_en }"
                    for="description_en"
                >
                    Description (English)
                </label>

                <vue-editor 
                    v-model="form.description_en"
                ></vue-editor>

                <p
                    v-if="errors.description_en"
                    v-text="errors.description_en[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.description_fr }"
                    for="description_fr"
                >
                    Description (French)
                </label>

                <vue-editor 
                    v-model="form.description_fr"
                ></vue-editor>

                <p
                    v-if="errors.description_fr"
                    v-text="errors.description_fr[0]"
                    class="text-red-500 text-sm"
                ></p>
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
            v-if="$can('manage assessments')"
            @close="cancel"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { VueEditor, Quill } from 'vue2-editor'

export default {
    components: {
        VueEditor
    },

    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                description_en: '',
                description_fr: '',
                score: null,
                section_id: '',
                question_category_id: null
            }
        }
    },

    computed: {
        ...mapGetters({
            question: 'questions/question',
            sections: 'sections/sections',
            questionCategories: 'questionCategories/questionCategories'
        })
    },
    
    methods: {
        ...mapActions({
            fetchSections: 'sections/fetch',
            fetchQuestionCategories: 'questionCategories/fetch',
        }),

        cancel () {
            window.events.$emit('questions:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.description_en = ''
            this.description_fr = ''
            this.score = null
            this.section_id = null
            this.question_category_id = null
        },

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/questions/${this.question.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        }
    },

    async mounted () {
        this.form.name_en = this.question.name_en
        this.form.name_fr = this.question.name_fr
        this.form.description_en = this.question.description_en
        this.form.description_fr = this.question.description_fr
        this.form.score = this.question.score
        this.form.section_id = this.question.section_id
        this.form.question_category_id = this.question.question_category_id

        await this.fetchSections()
        await this.fetchQuestionCategories()
    }
}
</script>