<template>
    <div>
        <div
            class="text-lg"
            :class="{ 'text-red-700': warningClass }"
            v-if="attempt.time_remaining"
        >
            <strong>Time remaining:</strong> {{ attempt.time_remaining }} minutes
        </div>

        <modal 
            v-show="modalActive"
            @submit="warningConfirmed"
            :has-cancel-button="false"
            ok-button-text="OK"
        >
            <template slot="header">
                Two minute warning
            </template>

            <template slot="body">
                <div class="my-4">
                    You have less than two minutes left to complete the assessment. 
                    Please check your work and submit. If the timer reaches 0 minutes, 
                    your work will automatically be saved and you will be locked 
                    out of this exam.
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data () {
        return {
            warningClass: false,
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            attempt: 'assessment/attempt'
        })
    },

    methods: {
        warningConfirmed () {
            this.modalActive = false

            if (!this.hasConfirmed) {
                this.hasConfirmed = true
            }
        }
    },

    mounted () {
        setInterval(() => {
           if (parseInt(this.attempt.time_remaining) <= 5) {
                this.warningClass = true
            }

            if (parseInt(this.attempt.time_remaining) === 2) {
                if (!this.hasConfirmed) {
                    this.modalActive = true
                }
            }
        }, 1000)

        window.events.$on('assessment:five-min-warning', () => this.warningClass = true)
    }
}
</script>