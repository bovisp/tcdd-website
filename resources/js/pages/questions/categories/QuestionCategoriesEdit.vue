<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_questions_categories_questioncategoriesedit.edit') }}: {{ trans('js_pages_questions_categories_questioncategoriesedit.questioncategory') }} - {{ questionCategory.name }}
        </h1>

        <form 
            @submit.prevent="update"
        >
            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name_en }"
                    for="name_en"
                >
                    {{ trans('js_pages_questions_categories_questioncategoriesedit.nameenglish') }}
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
                    {{ trans('js_pages_questions_categories_questioncategoriesedit.namefrench') }}
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
                    {{ trans('js_pages_questions_categories_questioncategoriesedit.descriptionenglish') }}
                </label>

                <textarea 
                    v-model="form.description_en"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    rows="8"
                    id="description_en"
                    :class="{ 'border-red-500': errors.description_en }"
                ></textarea>

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
                    {{ trans('js_pages_questions_categories_questioncategoriesedit.descriptionfrench') }}
                </label>

                <textarea 
                    v-model="form.description_fr"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="description"
                    rows="8"
                    :class="{ 'border-red-500': errors.description_fr }"
                ></textarea>

                <p
                    v-if="errors.description_fr"
                    v-text="errors.description_fr[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    {{ trans('js_pages_questions_categories_questioncategoriesedit.updatecategory') }}
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    {{ trans('js_pages_questions_categories_questioncategoriesedit.cancel') }}
                </button>
            </div>
        </form>

        <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

        <destroy-question-category 
            v-if="hasRole(['administrator'])"
            @close="cancel"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                description_en: '',
                description_fr: ''
            }
        }
    },

    computed: {
        ...mapGetters({
            questionCategory: 'questionCategories/questionCategory'
        })
    },
    
    methods: {
        cancel () {
            window.events.$emit('question-categories:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.description_en = ''
            this.description_fr = ''
        },

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/questions/categories/${this.questionCategory.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        }
    },

    async mounted () {
        this.form.name_en = this.questionCategory.name_en
        this.form.name_fr = this.questionCategory.name_fr
        this.form.description_en = this.questionCategory.description_en
        this.form.description_fr = this.questionCategory.description_fr
    }
}
</script>