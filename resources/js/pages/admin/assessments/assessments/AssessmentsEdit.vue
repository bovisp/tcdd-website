<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Edit: Assessment - {{ assessment.name }}
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
                    for="assessment_type_id"
                    class="block text-gray-700 font-bold mb-2"
                >
                    Type
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
                    Description (French)
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
                class="w-full lg:w-1/5 mb-4"
            >
                <label 
                    for="assessment_type_id"
                    class="block text-gray-700 font-bold mb-2"
                >
                    Type
                </label>

                <div class="relative">
                    <select 
                        id="visible"
                        v-model="form.visible"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.visible }"
                    >
                        <option value=""></option>

                        <option
                            :value="visibility.value"
                            v-for="visibility in visibilities"
                            :key="visibility.id"
                            v-text="visibility.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.visible"
                    v-text="errors.visible[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full lg:w-1/5 mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.total_score }"
                    for="total_score"
                >
                    Total score
                </label>

                <input 
                    type="number" 
                    v-model="form.total_score"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="total_score"
                    :class="{ 'border-red-500': errors.total_score }"
                >

                <p
                    v-if="errors.total_score"
                    v-text="errors.total_score[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Update assessment
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

        <destroy-assessment 
            v-if="hasRole(['administrator'])"
            @close="cancel"
        />
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
                description_en: '',
                description_fr: '',
                section_id: null,
                assessment_type_id: null,
                visible: 0,
                total_score: 0
            },
            visibilities: [
                { name: 'Hidden', value: 0 },
                { name: 'Visible', value: 1 }
            ]
        }
    },

    computed: {
        ...mapGetters({
            sections: 'sections/sections',
            types: 'assessmentTypes/assessmentTypes',
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            fetchSections: 'sections/fetch',
            fetchTypes: 'assessmentTypes/fetch'
        }),

        cancel () {
            window.events.$emit('assessments:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.description_en = ''
            this.form.description_fr = ''
            this.form.section_id = null
            this.form.type_id = null
            this.form.visibility = 0
            this.form.total_score = 0
        },

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/assessments/${this.assessment.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        }
    },

    async mounted () {
        await this.fetchSections()
        await this.fetchTypes()

        this.form.name_en = this.assessment.name_en
        this.form.name_fr = this.assessment.name_fr
        this.form.description_en = this.assessment.description_en
        this.form.description_fr = this.assessment.description_fr
        this.form.section_id = this.assessment.section_id
        this.form.assessment_type_id = this.assessment.assessment_type_id
        this.form.visibility = this.assessment.visibility
        this.form.total_score = this.assessment.total_score
    }
}
</script>