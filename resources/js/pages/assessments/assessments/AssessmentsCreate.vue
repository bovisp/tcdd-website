<template>
    <div class="w-full px-4 lg:px-0">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_assessments_assessments_assessmentscreate.newassessment') }}
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
                >
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.section') }}
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
                    for="assessment_type_id"
                    class="block text-gray-700 font-bold mb-2"
                >
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.type') }}
                </label>

                <div class="relative">
                    <select 
                        id="assessment_type_id"
                        v-model="form.assessment_type_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.assessment_type_id }"
                    >
                        <option value=""></option>

                        <option
                            :value="type.id"
                            v-for="type in types"
                            :key="type.id"
                            v-text="type.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.assessment_type_id"
                    v-text="errors.assessment_type_id[0]"
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
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.nameenglish') }}
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
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.namefrench') }}
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
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.descriptionenglish') }}
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
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.descriptionfrench') }}
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
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.completion_time }"
                    for="name_en"
                >
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.completiontime') }}
                </label>

                <input 
                    type="number" 
                    min="1"
                    v-model="form.completion_time"
                    class="shadow appearance-none border rounded w-full md:w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="completion_time"
                    :class="{ 'border-red-500': errors.completion_time }"
                >

                <p
                    v-if="errors.completion_time"
                    v-text="errors.completion_time[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.createassessment') }}
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    {{ trans('js_pages_assessments_assessments_assessmentscreate.cancel') }}
                </button>
            </div>
        </form>
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
                section_id: null,
                assessment_type_id: null,
                completion_time: null
            }
        }
    },

    computed: {
        ...mapGetters({
            sections: 'sections/sections',
            types: 'assessmentTypes/assessmentTypes'
        })
    },

    methods: {
        ...mapActions({
            fetchSections: 'sections/fetch',
            fetchTypes: 'assessmentTypes/fetch'
        }),

        cancel () {
            window.events.$emit('assessments:create-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.description_en = ''
            this.form.description_fr = ''
            this.form.section_id = null
            this.form.assessment_type_id = null
            this.form.completion_time = null
        },

        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/assessments`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    },

    async mounted () {
        await this.fetchSections()
        await this.fetchTypes()
    }
}
</script>