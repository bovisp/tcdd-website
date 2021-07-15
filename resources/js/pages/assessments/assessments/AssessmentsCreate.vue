<template>
    <div>
        <h1 class="title">
            {{ trans('js_pages_assessments_assessments_assessmentscreate.newassessment') }}
        </h1>

        <form>
            <b-field
                :label="trans('generic.section')"
                :type="errors.section_id ? 'is-danger' : ''"
                :message="errors.section_id ? errors.section_id[0] : ''"
            >
                <b-select 
                    expanded
                    v-model="form.section_id"
                >
                    <option
                        :value="section.id"
                        v-for="section in sections"
                        :key="section.id"
                        v-text="section.name"
                    ></option>
                </b-select>
            </b-field>

            <b-field
                :label="trans('generic.type')"
                :type="errors.assessment_type_id ? 'is-danger' : ''"
                :message="errors.assessment_type_id ? errors.assessment_type_id[0] : ''"
            >
                <b-select 
                    expanded
                    v-model="form.assessment_type_id"
                >
                    <option
                        :value="type.id"
                        v-for="type in types"
                        :key="type.id"
                        v-text="type.name"
                    ></option>
                </b-select>
            </b-field>

            <b-field 
                :label="trans('generic.nameenglish')"
                :type="errors.name_en ? 'is-danger' : ''"
                :message="errors.name_en ? errors.name_en[0] : ''"
            >
                <b-input v-model="form.name_en"></b-input>
            </b-field>

            <b-field 
                :label="trans('generic.namefrench')"
                :type="errors.name_fr ? 'is-danger' : ''"
                :message="errors.name_fr ? errors.name_fr[0] : ''"
            >
                <b-input v-model="form.name_fr"></b-input>
            </b-field>

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

            <b-field 
                :label="trans('generic.completiontime')"
                :type="errors.completion_time ? 'is-danger' : ''"
                :message="errors.completion_time ? errors.completion_time[0] : ''"
            >
                <b-numberinput 
                    v-model="form.completion_time"
                    type="is-info"
                ></b-numberinput>
            </b-field>

            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <b-button 
                            type="is-info is-small"
                            @click.prevent="store"
                        >
                            {{ trans('js_pages_assessments_assessments_assessmentscreate.createassessment') }}
                        </b-button>
                    </div>

                    <div class="level-item">
                        <b-button 
                            type="is-text is-small"
                            @click.prevent="cancel"
                        >
                            {{ trans('generic.cancel') }}
                        </b-button>
                    </div>
                </div>
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