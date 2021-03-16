<template>
    <div class="flex flex-col items-center w-full py-16">
        <h1 class="text-3xl mb-4 w-full flex items-center">
            {{ user.fullname }}
        </h1>

        <change-password />

        <user-assessment-participant-list />

        <completed-assessments />

        <!-- <role /> -->

        <!-- <reporting-structure /> -->
        
        <!-- <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

        <destroy-user 
            v-if="hasRole(['administrator'])"
        /> -->
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

    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        })
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
    },
}
</script>