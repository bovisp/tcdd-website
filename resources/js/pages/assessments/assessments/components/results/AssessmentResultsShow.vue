<template>
    <button
        class="btn btn-sm text-sm"
        :class="showing ? 'btn-red' : 'btn-text'"
        @click.prevent="status"
    >
        {{ showing ? trans('js_pages_assessments_assessments_components_results_assessmentresultsshow.recall') : trans('js_pages_assessments_assessments_components_results_assessmentresultsshow.publish') }} results
    </button>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    props: {
        attempt: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            showing: false
        }
    },

    methods: {
        ...mapActions({
            setReviewStatus: 'assessments/setReviewStatus'
        }),

        status () {
            this.showing = !this.showing

            this.setReviewStatus({
                status: this.showing,
                attemptId: this.attempt.id
            })

            window.events.$emit('assessment:review-status')
        }
    },

    mounted () {
        this.showing = this.attempt.show ? true : false
        
        window.events.$on('assessment:review-status-all', status => {
            this.showing = status
        })
    }
}
</script>