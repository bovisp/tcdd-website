<template>
    <div>
        <div
            v-for="page in assessment.pages"
        >
            <h4 class="text-lg font-bold">
                Page {{ page }}
            </h4>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    }
}
</script>