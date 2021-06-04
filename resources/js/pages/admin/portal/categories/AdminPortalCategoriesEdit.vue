<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_admin_portal_categories_adminportalcategoriesedit.edit') }}: {{ trans('js_pages_admin_portal_categories_adminportalcategoriesedit.category') }} - {{ category.name }}
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
                    {{ trans('js_pages_admin_portal_categories_adminportalcategoriesedit.nameenglish') }}
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
                    {{ trans('js_pages_admin_portal_categories_adminportalcategoriesedit.namefrench') }}
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
                    :class="{ 'text-red-500': errors.moodle_course_category_id }"
                    for="moodle_course_category_id"
                >
                    {{ trans('js_pages_admin_portal_categories_adminportalcategoriesedit.portalcategoryid') }}
                </label>

                <input 
                    type="number" 
                    v-model="form.moodle_course_category_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="moodle_course_category_id"
                    :class="{ 'border-red-500': errors.moodle_course_category_id }"
                >

                <p
                    v-if="errors.moodle_course_category_id"
                    v-text="errors.moodle_course_category_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.moodle_parent_course_category_id }"
                    for="moodle_parent_course_category_id"
                >
                    {{ trans('js_pages_admin_portal_categories_adminportalcategoriesedit.portalparentcategoryid') }}
                </label>

                <input 
                    type="number" 
                    v-model="form.moodle_parent_course_category_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="moodle_parent_course_category_id"
                    :class="{ 'border-red-500': errors.moodle_parent_course_category_id }"
                >

                <p
                    v-if="errors.moodle_parent_course_category_id"
                    v-text="errors.moodle_parent_course_category_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    {{ trans('js_pages_admin_portal_categories_adminportalcategoriesedit.updatecategory') }}
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    {{ trans('js_pages_admin_portal_categories_adminportalcategoriesedit.cancel') }}
                </button>
            </div>
        </form>

        <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

        <destroy-portal-category 
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
                moodle_course_category_id: null,
                moodle_parent_course_category_id: null
            }
        }
    },

    computed: {
        ...mapGetters({
            category: 'portalCategories/category'
        })
    },
    
    methods: {
        cancel () {
            window.events.$emit('portal-categories:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.moodle_course_category_id = null
            this.form.moodle_parent_course_category_id = null
        },

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/admin/portal/categories/${this.category.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    },

    async mounted () {
        this.form.name_en = this.category.name_en
        this.form.name_fr = this.category.name_fr
        this.form.moodle_course_category_id = this.category.moodle_course_category_id
        this.form.moodle_parent_course_category_id = this.category.moodle_parent_course_category_id
    }
}
</script>