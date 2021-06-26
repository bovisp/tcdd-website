<template>
    <div>
        <assessment-participants-index 
            v-if="showIndex && visible"
            @create="showIndex = false"
        />

        <assessment-participants-create 
            v-if="!showIndex && visible"
            @cancel="reload"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        visible: {
            type: Boolean,
            required: true
        }
    },

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