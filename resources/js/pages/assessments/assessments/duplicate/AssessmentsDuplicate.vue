<template>
    <div>
        <tabs>
            <tab  
                :name="trans('js_pages_assessments_assessments_duplicate_assessmentsduplicate.editsettings')" 
                :selected="true"
            >
                <assessment-edit-form />
            </tab>

            <tab  
                :name="trans('js_pages_assessments_assessments_duplicate_assessmentsduplicate.instructors')" 
            >
                <assessment-instructors />
            </tab>

            <tab  
                :name="trans('js_pages_assessments_assessments_duplicate_assessmentsduplicate.participants')" 
            >
                <assessment-participants />
            </tab>

            <tab  
                :name="trans('js_pages_assessments_assessments_duplicate_assessmentsduplicate.questions')" 
            >
                <assessment-questions />
            </tab>
        </tabs>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    props: {
        duplicateForm: {
            type: Object,
            required: true
        }
    },

    methods: {
        ...mapActions({
            duplicateAssesment: 'assessments/duplicateAssesment'
        })
    },

    async mounted () {
        await this.duplicateAssesment(this.duplicateForm)
    }
}
</script>