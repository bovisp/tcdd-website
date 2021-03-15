<template>
    <button
        class="btn btn-sm text-sm"
        :class="showing ? 'btn-red' : 'btn-text'"
        @click.prevent="showing = !showing"
    >
        {{ showing ? 'Hide' : 'Show' }} results
    </button>
</template>

<script>
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

    mounted () {
        this.showing = this.attempt.show ? true : false
        
        window.events.$on('assessment:review-status-all', status => {
            this.showing = status
        })
    }
}
</script>