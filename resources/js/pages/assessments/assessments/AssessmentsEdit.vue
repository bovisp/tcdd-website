<template>
    <div>
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <b-button
                        type="is-text"
                        @click.prevent="back"
                    >{{ trans('js_pages_assessments_assessments_assessmentsedit.backtoassessments') }}</b-button>
                </div>
            </div>

            <div class="level-right">
                <div class="level-item">
                    <b-button
                        :type="lockBtnType"
                        @click.prevent="setAssessmentLockStatus"
                    >{{ lockText }}</b-button>
                </div>
            </div>
        </div>

        <h1 class="title" id="title">
            {{ trans('generic.edit') }}: {{ trans('generic.assessment') }} - {{ assessment.name }}
        </h1>

        <b-message
            v-if="assessment.marking_completed"
            type="is-info"
        >
            {{ trans('js_pages_assessments_assessments_assessmentsedit.markingassessmentcompleted') }} {{ dayjs(assessment.marking_completed_on).format('YYYY-MM-DD') }}.
        </b-message>

        <b-tabs 
            v-model="activeTab"
            :animated="false"
            type="is-boxed"
        >
            <b-tab-item :label="trans('generic.assessment')">
                <assessment-pages />
            </b-tab-item>

            <b-tab-item :label="trans('generic.instructors')">
                <assessment-instructors />
            </b-tab-item>

            <b-tab-item :label="trans('generic.participants')">
                <assessment-participants />
            </b-tab-item>

            <b-tab-item :label="trans('generic.settings')">
                <assessment-edit-form />
            </b-tab-item>

            <b-tab-item 
                :label="trans('generic.marking')"
                v-if="attemptAnswers.length"
            >
                <assessment-marking />
            </b-tab-item>

            <b-tab-item 
                :label="trans('js_pages_assessments_assessments_assessmentsedit.results')"
                v-if="attemptAnswers.length"
            >
                <assessment-results />
            </b-tab-item>
        </b-tabs>
    </div>
</template>

<script>
import { mapGetters, mapActions,mapMutations } from 'vuex'
import dayjs from 'dayjs'

export default {
    data () {
        return {
            activeTab: 0,
            counter: 0
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

        lockBtnType () {
            return this.assessment.locked ? 'is-danger' : 'is-success'
        }
    },

    watch: {
        'assessment.locked' () {
            if (this.counter === 0) {
                this.counter += 1

                return
            }
            
            this.$buefy.toast.open({
                message: 'Lock status updated',
                type: 'is-success'
            })
        }
    },

    methods: {
        ...mapActions({
            fetchAssessment: 'assessments/fetchAssessment',
            setAssessmentLockStatus: 'assessments/setAssessmentLockStatus',
            fetchAttempt: 'assessments/fetchAttempt',
            fetchAttempts: 'assessments/fetchAttempts',
            fetchParticipantAnswer: 'assessments/fetchParticipantAnswer',
            fetchParticipantAnswers: 'assessments/fetchParticipantAnswers',
            updateAssessmentMarkingCompletion: 'assessments/updateAssessmentMarkingCompletion'
        }),

        ...mapMutations({
            setLockStatus: 'assessments/SET_LOCK_STATUS'
        }),

        dayjs,

        back () {
            window.events.$emit('assessments:edit-cancel')
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

                window.events.$emit('assessments:recalculate-average', this.attemptAnswers)
            })

        Echo.private(`assessment.${this.assessment.id}.attempting`)
            .listen('AssessmentAttemptStarted', async (e) => {
                await this.fetchAssessment(this.assessment.id)
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