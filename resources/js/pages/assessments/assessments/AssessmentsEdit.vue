<template>
    <div class="w-full">
        <div 
            class="mb-4 flex items-center"
            v-if="!duplicating"
        >
            <button 
                class="btn btn-text"
                @click.prevent="back"
            >
                <i class="fas fa-chevron-left mr-1"></i>

                Back to assessments
            </button>

            <button 
                class="btn ml-auto"
                :class="lockStatus ? 'btn-red' : 'btn-green'"
                @click.prevent="setAssessmentLockStatus"
            >
                {{ lockText }}
            </button>
        </div>

        <h1 class="text-3xl font-bold mb-4">
            {{ duplicating ? 'Duplicating' : 'Edit' }}: Assessment - {{ assessment.name }}
        </h1>

        <tabs v-if="!duplicating">
            <tab  
                name="Edit settings" 
                :selected="true"
            >
                <assessment-edit-form
                    @assessments:duplicate="duplicate"
                />
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

        <assessments-duplicate 
            v-else
            :duplicate-form="duplicateForm"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            duplicating: false,
            duplicateForm: {}
        }
    },
    
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
        }),

        duplicate (form) {
            this.duplicating = true

            this.duplicateForm = form
        },

        back () {
            window.events.$emit('assessments:edit-cancel')
        }
    }
}
</script>