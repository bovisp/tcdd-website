<template>
    <div>
        <assessment-pages 
            :pages="pages"
            @assessment-pages:add="addPage"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data () {
        return {
            pages: []
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        addPage (page) {
            this.pages.push(page)
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/assessments/${this.assessment.id}/page`)

        this.pages = data.data
    }
}
</script>