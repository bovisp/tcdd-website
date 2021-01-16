<template>
    <div>
        <assessment-pages />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'assessments/fetchPages'
        })
    },

    async mounted () {
        await this.fetch(this.assessment.id)
    }
}
</script>