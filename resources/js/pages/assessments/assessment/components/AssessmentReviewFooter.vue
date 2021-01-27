<template>
    <div>
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

            <button 
                class="btn btn-blue ml-4"
                @click.prevent="modalActive = true"
            >
                Submit and finish
            </button>
        </div>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="submit"
        >
            <template slot="header">
                Submit {{ attempt.assessment.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    Are you sure you want to submit this exam? Once you submit your exam questions, you will not be 
                    allowed to re-enter this assessment.
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            attempt: 'assessment/attempt'
        })
    },

    methods: {
        ...mapActions({
            setReviewStatus: 'assessment/setReviewStatus',
            submitAssessment: 'assessment/submitAssessment'
        }),

        review () {
            this.setReviewStatus(false)
        },
        
        close () {
            this.modalActive = false
        },

        async submit () {
            this.submitAssessment()
        }
    }
}
</script>