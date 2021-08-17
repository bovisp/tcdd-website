<template>
    <div>
        <h1 class="title">
            {{ trans('generic.edit') }}: 
            {{ trans('generic.assessmenttype') }} - 
            {{ type.name }}
        </h1>

        <form>
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

            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <b-button 
                            type="is-info is-small"
                            @click.prevent="update"
                        >
                            {{ trans('js_pages_assessments_assessment-types_assessmenttypesedit.updateassessmenttype') }}
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