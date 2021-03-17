<template>
    <div class="w-full" v-if="attempt">
        <div class="flex items-center w-full">
            <h2 class="text-3xl font-medium mb-2">
                Review: {{ attempt.title }}
            </h2>

            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                Return to profile
            </button>
        </div>

        <div class="mt-4">
            <strong>Section: </strong> {{ attempt.section }}
        </div>

        <div class="mt-2">
            <strong>Type: </strong> {{ attempt.type }}
        </div>

        <div class="mt-2">
            <strong>Marked: </strong> {{ dayjs(attempt.marked_on).format('YYYY-MM-DD') }}
        </div>

        <div class="mt-2">
            <strong>Score: </strong> {{ attempt.participant_score }}/{{ attempt.total_score }} ({{ attempt.percentage }}%)
        </div>

        <hr class="my-6">

        <h3 class="text-2xl font-light mb-4">
            Results
        </h3>

        <div class="flex items-center w-full">
            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                Return to profile
            </button>
        </div>
    </div>
</template>

<script>
import dayjs from 'dayjs'

export default {
    props: {
        attemptId: {
            type: Number,
            required: true
        }
    },
    
    data () {
        return {
            attempt: null
        }
    },

    methods: {
        dayjs,

        cancel () {
            this.$emit('assessment:review-cancel')
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/users/${this.authUser.id}/attempt/${this.attemptId}/review`)

        this.attempt = data.data
    }
}
</script>