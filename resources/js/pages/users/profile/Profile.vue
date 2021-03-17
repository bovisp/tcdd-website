<template>
    <div class="flex flex-col items-center w-full py-16">
        <template v-if="!reviewing">
            <h1 class="text-3xl mb-4 w-full flex items-center">
                {{ user.fullname }}
            </h1>

            <change-password />

            <user-assessment-participant-list />

            <completed-assessments 
                @assessment:review="review"
            />

            <!-- <role /> -->

            <!-- <reporting-structure /> -->
            
            <!-- <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

            <destroy-user 
                v-if="hasRole(['administrator'])"
            /> -->
        </template>
        
        <template v-else>
            <attempt-review 
                :attempt-id="attemptId"
                @assessment:review-cancel="cancelReview"
            />
        </template>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    props: {
        userId: {
            type: Number,
            required: true
        }
    },

    data () {
        return {
            reviewing: false,
            attemptId: null
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        }),

        review (attemptId) {
            this.attemptId = attemptId

            this.reviewing = true
        },

        cancelReview () {
            this.attemptId = null

            this.reviewing = false
        }
    },

    async mounted() {
        await this.fetch(this.userId)

        Echo.private(`user.${this.userId}.assessment_activated`)
            .listen('AddAssessmentToProfilePage', async (e) => {
                await this.fetch(this.userId)
            })

        Echo.private(`user.${this.userId}.assessment_results`)
            .listen('PublishAssessmentAttempt', async (e) => {
                await this.fetch(this.userId)
            })

        Echo.private(`user.${this.userId}.attempt_show`)
            .listen('ShowAttemptResults', async (e) => {
                await this.fetch(this.userId)
            })
    },
}
</script>