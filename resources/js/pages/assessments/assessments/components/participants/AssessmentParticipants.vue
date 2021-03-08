<template>
    <div>
        <assessment-participants-index 
            v-if="showIndex"
            @create="showIndex = false"
        />

        <assessment-participants-create 
            v-else
            @cancel="reload"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            showIndex: true
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            fetchAssessment: 'assessments/fetchAssessment'
        }),

        async reload () {
            this.showIndex = true

            await this.fetchAssessment(this.assessment.id)
        },
    }
}
</script>