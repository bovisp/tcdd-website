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
                :class="lockClass"
                @click.prevent="setAssessmentLockStatus"
            >
                {{ lockText }}
            </button>
        </div>

        <h1 class="text-3xl font-bold mb-4">
            {{ duplicating ? 'Duplicating' : 'Edit' }}: Assessment - {{ assessment.name }}
        </h1>

        <div
            class="alert alert-blue my-4"
            v-if="assessment.marking_completed"
        >
            Marking for this assessment was completed on {{ dayjs(assessment.marking_completed_on).format('YYYY-MM-DD') }}.
        </div>

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
                @tabs:isactive="initiateInstructorTab"
            >
                <assessment-instructors 
                    v-if="showInstructors"
                />
            </tab>

            <tab  
                name="Participants" 
                @tabs:isactive="initiateParticipantTab"
            >
                <assessment-participants 
                    v-if="showParticipants"
                />
            </tab>

            <tab  
                name="Questions" 
            >
                <assessment-questions />
            </tab>

            <tab  
                name="Marking" 
                v-if="attemptAnswers.length"
            >
                <assessment-marking />
            </tab>

            <tab  
                name="Results" 
                v-if="attemptAnswers.length"
            >
                <assessment-results />
            </tab>
        </tabs>

        <assessments-duplicate 
            v-else
            :duplicate-form="duplicateForm"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'
import dayjs from 'dayjs'

export default {
    data () {
        return {
            duplicating: false,
            duplicateForm: {},
            showInstructors: false,
            showParticipants: false
        }
    },
    
    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment',
            attempts: 'assessments/attempts',
            attemptAnswers: 'assessments/attemptAnswers'
        }),

        lockText () {
            return this.assessment.locked ? 'Assessment locked' : 'Lock assessment'
        },

        lockClass () {
            return this.assessment.locked ? 'btn-red' : 'btn-green'
        }
    },

    methods: {
        ...mapActions({
            setAssessmentLockStatus: 'assessments/setAssessmentLockStatus',
            fetchAttempt: 'assessments/fetchAttempt',
            fetchAttempts: 'assessments/fetchAttempts',
            fetchAssessment: 'assessments/fetchAssessment',
            fetchParticipantAnswer: 'assessments/fetchParticipantAnswer',
            fetchParticipantAnswers: 'assessments/fetchParticipantAnswers',
            updateAssessmentMarkingCompletion: 'assessments/updateAssessmentMarkingCompletion'
        }),

        ...mapMutations({
            setLockStatus: 'assessments/SET_LOCK_STATUS'
        }),

        dayjs,

        duplicate (form) {
            this.duplicating = true

            this.duplicateForm = form
        },

        back () {
            window.events.$emit('assessments:edit-cancel')
        },

        initiateInstructorTab () {
            this.showInstructors = true
            this.showParticipants = false
        },

        initiateParticipantTab () {
            this.showInstructors = false
            this.showParticipants = true
        }
    },

    async mounted () {
        await this.fetchAssessment(this.assessment.id)

        await this.fetchAttempts()

        await this.fetchParticipantAnswers()

        Echo.private(`assessment.${this.assessment.id}`)
            .listen('AssessmentCompleted', async (e) => {
                await this.fetchAssessment(this.assessment.id)

                await this.fetchAttempt(e.attemptId)

                await this.fetchParticipantAnswer(e.attemptId)
            })

        Echo.private(`assessment.${this.assessment.id}.attempting`)
            .listen('AssessmentAttemptStarted', async (e) => {
                await this.setLockStatus(true)
            })

        Echo.private(`assessment.${this.assessment.id}.marked`)
            .listen('AssessmentAttemptsMarked', async (e) => {
                await this.updateAssessmentMarkingCompletion({
                    assessmentId: e.assessmentId,
                    markingCompletedOn: e.markingCompletedOn
                })
            })
    }
}
</script>