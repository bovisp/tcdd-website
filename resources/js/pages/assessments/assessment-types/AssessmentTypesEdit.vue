<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_assessments_assessment-types_assessmenttypesedit.edit') }}: {{ trans('js_pages_assessments_assessment-types_assessmenttypesedit.assessmenttype') }} - {{ type.name }}
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
                    {{ trans('js_pages_assessments_assessment-types_assessmenttypesedit.nameenglish') }}
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
                    {{ trans('js_pages_assessments_assessment-types_assessmenttypesedit.namefrench') }}
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
                    {{ trans('js_pages_assessments_assessment-types_assessmenttypesedit.updateassessmenttype') }}
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    {{ trans('js_pages_assessments_assessment-types_assessmenttypesedit.cancel') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            form: {
                name_en: '',
                name_fr: ''
            }
        }
    },

    computed: {
        ...mapGetters({
            type: 'assessmentTypes/assessmentType'
        })
    },
    
    methods: {
        cancel () {
            window.events.$emit('assessment-types:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
        },

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/assessments/assessment-types/${this.type.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        }
    },

    async mounted () {
        this.form.name_en = this.type.name_en
        this.form.name_fr = this.type.name_fr
    }
}
</script>