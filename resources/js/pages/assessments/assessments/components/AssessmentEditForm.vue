<template>
    <div>
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
                    {{ trans('generic.descriptionenglish') }}
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
                    {{ trans('generic.descriptionfrench') }}
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
                    :disabled="assessment.locked"
                ></b-numberinput>
            </b-field>

            <div class="level mt-6">
                <div class="level-left">
                    <div class="level-item">
                        <b-button 
                            type="is-info is-small"
                            @click.prevent="update"
                        >
                            {{ trans('generic.update') }} {{ trans('generic.assessment') }}
                        </b-button>
                    </div>

                    <template v-if="!isDuplicating">
                        <div class="level-item">
                            <b-button 
                                type="is-text is-small"
                                @click.prevent="duplicate"
                            >
                                {{ trans('generic.duplicate') }}
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
                    </template>
                </div>
            </div>
        </form>

        <hr>

        <destroy-assessment 
            v-if="hasRole(['administrator']) && !assessment.locked"
            @close="cancel"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
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
            types: 'assessmentTypes/assessmentTypes',
            assessment: 'assessments/assessment',
            isDuplicating: 'assessments/isDuplicating'
        })
    },

    methods: {
        ...mapActions({
            fetchSections: 'sections/fetch',
            fetchTypes: 'assessmentTypes/fetch',
            duplicateAssessment: 'assessments/duplicateAssessment',
            setAssessment: 'assessments/setEdit',
            getMissingProp: 'assessments/getMissingProp'
        }),

        ...mapMutations({
            setDuplicationStatus: 'assessments/SET_DUPLICATION_STATUS'
        }),

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/assessments/${this.assessment.id}`, this.form)

            await this.setAssessment(data.data.assessment)

            await this.setDuplicationStatus(false)

            this.$scrollTo('#title')

            this.$buefy.toast.open({
                message: data.data.message,
                type: 'is-success'
            })
        },

        cancel () {
            window.events.$emit('assessments:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.description_en = ''
            this.form.description_fr = ''
            this.form.section_id = null
            this.form.type_id = null
            this.form.completion_time = null
        },

        async duplicate () {
            await this.duplicateAssessment()

            await this.populateForm()

            this.$scrollTo('#title')

            this.$buefy.toast.open({
                message: 'Assessment successfully duplicated.',
                type: 'is-success'
            })
        },

        async cancelDuplication () {

        },

        populateForm () {
            this.form.name_en = this.assessment.name_en
            this.form.name_fr = this.assessment.name_fr
            this.form.description_en = this.assessment.description_en
            this.form.description_fr = this.assessment.description_fr
            this.form.section_id = this.assessment.section_id
            this.form.assessment_type_id = this.assessment.assessment_type_id
            this.form.completion_time = this.assessment.completion_time
        }
    },

    async mounted () {
        await this.fetchSections()
        await this.fetchTypes()
        await this.populateForm()

        // let emptyProps = []

        for (let prop in this.form) {
            if (!this.form[prop]) {
                // this.form[prop] = this.$store.state.assessments.assessment[prop]
                // console.log(`assessment: ${this.assessment[prop]} and form ${this.form[prop]} `)
                // console.log(prop)
                this.form[prop] = await this.getMissingProp(prop)
            }
        }

        // console.log(emptyProps)
        // console.log(this.form)
        // console.log(this.$store.state.assessments.assessment)
    }
}
</script>