<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Edit: Assessment - {{ assessment.name }}
        </h1>

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
        </tabs>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    }
}
</script>