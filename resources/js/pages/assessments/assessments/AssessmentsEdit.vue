<template>
    <div class="w-full">
        <div class="mb-4 flex justify-end">
            <button 
                class="btn"
                :class="lockStatus ? 'btn-red' : 'btn-green'"
                @click.prevent="setAssessmentLockStatus"
            >
                {{ lockText }}
            </button>
        </div>

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

            <tab  
                name="Questions" 
            >
                <assessment-questions />
            </tab>
        </tabs>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment',
            lockStatus: 'assessments/lockStatus'
        }),

        lockText () {
            return this.lockStatus ? 'Assessment locked' : 'Lock assessment'
        }
    },

    methods: {
        ...mapActions({
            setAssessmentLockStatus: 'assessments/setAssessmentLockStatus'
        })
    }
}
</script>