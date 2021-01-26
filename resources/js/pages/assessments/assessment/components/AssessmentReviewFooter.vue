<template>
    <div
        class="fixed h-16 w-full bottom-0 left-0 mx-auto flex items-center px-8 bg-gray-100"
        style="box-shadow: 0px 0 10px rgba(0, 0, 0, 0.1);"
    >
        <span
            class="text-lg"
            v-if="attempt.time_remaining"
        >
            <strong>Time remaining:</strong> {{ attempt.time_remaining }} minutes
        </span>

        <button 
            class="btn btn-green ml-auto"
            @click.prevent="review"
        >
            Return to assessment
        </button>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            attempt: 'assessment/attempt'
        })
    },

    methods: {
        ...mapActions({
            setReviewStatus: 'assessment/setReviewStatus'
        }),

        review () {
            this.setReviewStatus(false)
        }
    }
}
</script>