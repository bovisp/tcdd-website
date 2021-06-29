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

                {{ trans('js_pages_assessments_assessments_assessmentsedit.backtoassessments') }}
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
            {{ duplicating ? trans('js_pages_assessments_assessments_assessmentsedit.duplicating') : trans('js_pages_assessments_assessments_assessmentsedit.edit') }}: {{ trans('js_pages_assessments_assessments_assessmentsedit.assessment') }} - {{ assessment.name }}
        </h1>

        <div
            class="alert alert-blue my-4"
            v-if="assessment.marking_completed"
        >
            {{ trans('js_pages_assessments_assessments_assessmentsedit.markingassessmentcompleted') }} {{ dayjs(assessment.marking_completed_on).format('YYYY-MM-DD') }}.
        </div>

        <tabs v-if="!duplicating">
            <tab  
                :name="trans('js_pages_assessments_assessments_assessmentsedit.editsettings')" 
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
                    :visible="showInstructors"
                />
            </tab>

            <tab  
                name="Participants" 
                @tabs:isactive="initiateParticipantTab"
            >
                <assessment-participants 
                    :visible="showParticipants"
                />
            </tab>

            <tab  
                :name="trans('js_pages_assessments_assessments_assessmentsedit.questions')" 
            >
                <assessment-questions />
            </tab>

            <tab  
                :name="trans('js_pages_assessments_assessments_assessmentsedit.marking')" 
                v-if="attemptAnswers.length"
            >
                <assessment-marking />
            </tab>

            <tab  
                :name="trans('js_pages_assessments_assessments_assessmentsedit.results')" 
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
            return this.assessment.locked ? this.trans('js_pages_assessments_assessments_assessmentsedit.assessmentlocked') : this.trans('js_pages_assessments_assessments_assessmentsedit.lockassessment')
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