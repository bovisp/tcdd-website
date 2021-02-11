<template>
    <div>
        <tabs>
            <tab  
                name="Edit settings" 
                :selected="true"
            >
                <assessment-edit-form />
            </tab>

            <tab  
                name="Instructors" 
            >
                <assessment-instructors />
            </tab>

            <tab  
                name="Participants" 
            >
                <assessment-participants />
            </tab>

            <tab  
                name="Questions" 
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