<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_admin_portal_courses_adminportalcoursescreate.newcourse') }}
        </h1>

        <form 
            @submit.prevent="store"
        >
            <div
                class="w-full lg:w-1/3 mb-4"
            >
                <label 
                    for="portal_language_id"
                    class="block text-gray-700 font-bold mb-2"
                >
                    {{ trans('js_pages_admin_portal_courses_adminportalcoursescreate.language') }}
                </label>

                <div class="relative">
                    <select 
                        id="portal_language_id"
                        v-model="form.portal_language_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.portal_language_id }"
                    >
                        <option value=""></option>

                        <option
                            :value="language.id"
                            v-for="language in languages"
                            :key="language.id"
                            v-text="language.language"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.portal_language_id"
                    v-text="errors.portal_language_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full lg:w-1/3 mb-4"
            >
                <label 
                    for="portal_category_id"
                    class="block text-gray-700 font-bold mb-2"
                >
                    {{ trans('js_pages_admin_portal_courses_adminportalcoursescreate.category') }}
                </label>

                <div class="relative">
                    <select 
                        id="portal_category_id"
                        v-model="form.portal_category_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.portal_category_id }"
                    >
                        <option value=""></option>

                        <option
                            :value="category.id"
                            v-for="category in categories"
                            :key="category.id"
                            v-text="`${category.moodle_course_category_id} - ${category.name}`"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.portal_category_id"
                    v-text="errors.portal_category_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full lg:w-1/3 mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.moodle_course_id }"
                    for="moodle_course_id"
                >
                    {{ trans('js_pages_admin_portal_courses_adminportalcoursescreate.portalcourseid') }}
                </label>

                <input 
                    type="number" 
                    v-model="form.moodle_course_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="moodle_course_id"
                    :class="{ 'border-red-500': errors.moodle_course_id }"
                >

                <p
                    v-if="errors.moodle_course_id"
                    v-text="errors.moodle_course_id[0]"
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
                    {{ trans('js_pages_admin_portal_courses_adminportalcoursescreate.nameenglish') }}
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
                    {{ trans('js_pages_admin_portal_courses_adminportalcoursescreate.namefrench') }}
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
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    {{ trans('js_pages_admin_portal_courses_adminportalcoursescreate.addcourse') }}
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    {{ trans('js_pages_admin_portal_courses_adminportalcoursescreate.cancel') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                portal_language_id: null,
                portal_category_id: null,
                moodle_course_id: null
            }
        }
    },

    computed: {
        ...mapGetters({
            languages: 'portalLanguages/languages',
            categories: 'portalCategories/categories'
        })
    },

    methods: {
        ...mapActions({
            fetchLanguages: 'portalLanguages/fetch',
            fetchCategories: 'portalCategories/fetch'
        }),

        cancel () {
            window.events.$emit('portal-courses:create-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.portal_language_id = null
            this.form.portal_category_id = null
            this.form.moodle_course_id = null
        },

        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/admin/portal/courses`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    },

    async mounted () {
        await this.fetchLanguages()
        await this.fetchCategories()
    }
}
</script>