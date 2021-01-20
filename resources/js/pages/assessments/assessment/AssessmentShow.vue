<template>
    <div>
        {{ attempt }}
    </div>
</template>

<script>
import { mapActions, mapMutations, mapGetters } from 'vuex'

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
            attempt: 'assessment/attempt'
        })
    },

    methods: {
        ...mapActions({
            checkTimeRemaining: 'assessment/checkTimeRemaining',
            fetch: 'assessment/fetch'
        })
    },

    async mounted () {
        await this.fetch({
            attempt: this.assessmentAttempt,
            assessment: this.assessment
        })

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