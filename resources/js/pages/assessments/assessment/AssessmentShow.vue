<template>
    <div v-if="attempt"
        class="mt-8"
    >
        <div class="flex items-center">
            <h1 
                class="font-normal text-4xl"
                v-if="attempt.assessment"
            >
                {{ attempt.assessment.name }}
            </h1>

            <span
                class="ml-auto text-xl"
                v-if="totalScore"
            ><strong>{{ trans('js_pages_assessments_assessment_assessmentshow.totalscore') }}:</strong> {{ totalScore }} {{ trans('js_pages_assessments_assessment_assessmentshow.points') }}</span>
        </div>

        <assessment-attempt-page 
            v-if="!reviewStatus"
        />

        <assessment-attempt-review 
            v-if="reviewStatus"
        />

        <assessment-attempt-footer
            :assessment="assessment"
        />

        <div class="py-16"></div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    props: {
        assessmentAttempt: {
            type: Object,
            required: true
        },
        assessment: {
            type: Object,
            required: true
        }
    },

    computed: {
        ...mapGetters({
            attempt: 'assessment/attempt',
            totalScore: 'assessment/totalScore',
            reviewStatus: 'assessment/reviewStatus',
        })
    },

    methods: {
        ...mapActions({
            fetch: 'assessment/fetch',
            getTotalScore: 'assessment/getTotalScore',
            fetchAttemptForm: 'assessment/fetchAttemptForm',
            checkTimeRemaining: 'assessment/checkTimeRemaining'
        })
    },

    async mounted () {
        await this.fetch({
            attempt: this.assessmentAttempt,
            assessment: this.assessment
        })

        await this.getTotalScore()       

        if (this.assessment.completion_time) {
            await this.checkTimeRemaining()

            setInterval(async () => {
                await this.checkTimeRemaining()
            }, 60000)
        }
    }
}
</script>