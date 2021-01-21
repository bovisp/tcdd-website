<template>
    <div v-if="currentPage">
        <h2 class="font-normal text-2xl mt-6">
            Page {{ currentPage.number }}
        </h2>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            currentPage: 'assessment/currentPage'
        })
    }
}
</script>