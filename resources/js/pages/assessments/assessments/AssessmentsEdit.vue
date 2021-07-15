<template>
    <div>
        <h1 class="title">
            {{ trans('generic.edit') }}: {{ trans('generic.assessment') }} - {{ assessment.name }}
        </h1>

        <b-tabs 
            v-model="activeTab"
            :animated="false"
            type="is-boxed"
        >
            <b-tab-item :label="trans('generic.assessment')">
                Assessment
            </b-tab-item>

            <b-tab-item :label="trans('generic.instructors')">
                Instructors
            </b-tab-item>

            <b-tab-item :label="trans('generic.participants')">
                Participants
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
import { mapGetters, mapActions, mapMutations } from 'vuex'

export default {
    data () {
        return {
            activeTab: 0
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            fetchAssessment: 'assessments/fetchAssessment'
        }),

        duplicate (form) {
            this.duplicating = true

            this.duplicateForm = form
        }
    },

    async mounted () {
        await this.fetchAssessment(this.assessment.id)
    }
}
</script>