<template>
    <div>
        <div
            class="text-lg"
            :class="{ 'text-red-700': warningClass }"
            v-if="attempt.time_remaining"
        >
            <strong>{{ trans('js_pages_assessments_assessment_components_assessmentattempttimer.timeremaining') }}:</strong> {{ attempt.time_remaining }} {{ trans('js_pages_assessments_assessment_components_assessmentattempttimer.minutes') }}
        </div>

        <modal 
            v-show="modalActive"
            @submit="warningConfirmed"
            :has-cancel-button="false"
            ok-button-text="OK"
            cancel-button-text="Cancel"
        >
            <template slot="header">
                {{ trans('js_pages_assessments_assessment_components_assessmentattempttimer.twominute') }}
            </template>

            <template slot="body">
                <div class="my-4">
                    {{ trans('js_pages_assessments_assessment_components_assessmentattempttimer.twominutetext') }}
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