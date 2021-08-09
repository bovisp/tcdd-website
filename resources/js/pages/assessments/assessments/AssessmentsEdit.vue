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
                <assessment-edit-form
                    @assessments:duplicate="duplicate"
                />
            </b-tab-item>
        </b-tabs>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            activeTab: 0
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        }),

        lockText () {
            return this.assessment.locked ? this.trans('js_pages_assessments_assessments_assessmentsedit.assessmentlocked') : this.trans('js_pages_assessments_assessments_assessmentsedit.lockassessment')
        },

        lockBtnType () {
            return this.assessment.locked ? 'is-danger' : 'is-success'
        }
    },

    methods: {
        ...mapActions({
            fetchAssessment: 'assessments/fetchAssessment',
            setAssessmentLockStatus: 'assessments/setAssessmentLockStatus'
        }),

        duplicate (form) {
            this.duplicating = true

            this.duplicateForm = form
        },

        back () {
            window.events.$emit('assessments:edit-cancel')
        }
    },

    async mounted () {
        await this.fetchAssessment(this.assessment.id)
    }
}
</script>