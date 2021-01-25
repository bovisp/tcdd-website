<template>
    <div v-if="attempt">
        <div class="flex items-center mt-8">
            <h1 
                class="font-normal text-4xl"
                v-if="attempt.assessment"
            >
                {{ attempt.assessment.name }}
            </h1>

            <span
                class="ml-auto text-xl"
                v-if="totalScore"
            ><strong>Total score:</strong> {{ totalScore }} points</span>
        </div>

        <assessment-attempt-page />

        <assessment-attempt-footer />
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
            totalScore: 'assessment/totalScore'
        })
    },

    methods: {
        ...mapActions({
            checkTimeRemaining: 'assessment/checkTimeRemaining',
            fetch: 'assessment/fetch',
            getTotalScore: 'assessment/getTotalScore',
            fetchAttemptForm: 'assessment/fetchAttemptForm'
        })
    },

    async mounted () {
        await this.fetch({
            attempt: this.assessmentAttempt,
            assessment: this.assessment
        })

        await this.fetchAttemptForm()

        await this.getTotalScore()

        console.log(this.attempt)

        if (this.assessment.completion_time) {
            await this.checkTimeRemaining()

            setInterval(async () => {
                await this.checkTimeRemaining()
            }, 60000)
        }       
    }
}
</script>