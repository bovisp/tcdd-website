<template>
    <button
        class="btn btn-sm text-sm"
        :class="showing ? 'btn-red' : 'btn-text'"
        @click.prevent="status"
    >
        {{ showing ? 'Hide' : 'Show' }} all results
    </button>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { filter } from 'lodash-es'

export default {
    data () {
        return {
            showing: false
        }
    },

    computed: {
        ...mapGetters({
            attemptAnswers: 'assessments/attemptAnswers'
        })
    },

    methods: {
        ...mapActions({
            setReviewStatusAll: 'assessments/setReviewStatusAll'
        }),

        status () {
            this.showing = !this.showing

            this.setReviewStatusAll(this.showing)

            window.events.$emit('assessment:review-status-all', this.showing)
        }
    },

    mounted () {
        window.events.$on('assessment:review-status', () => {
            if (filter(this.attemptAnswers, answer => answer.show).length + 1 === this.attemptAnswers.length) {
                this.showing = true
            }

            if (filter(this.attemptAnswers, answer => answer.show).length - 1 === 0) {
                this.showing = false
            }
        })

        if (filter(this.attemptAnswers, answer => answer.show).length === this.attemptAnswers.length) {
            this.showing = true
        }
    }
}
</script>