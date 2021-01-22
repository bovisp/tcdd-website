<template>
    <div v-if="currentPage">
        <h2 class="font-normal text-2xl my-6">
            Page {{ currentPage.number }} 
            <span v-if="currentPageScore">({{ currentPageScore }} points)</span>
        </h2>

        <assessment-attempt-page-content 
            v-for="content in currentPage.content"
            :key="content.id"
            :content="content"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { isEmpty } from 'lodash-es'

export default {
    computed: {
        ...mapGetters({
            currentPage: 'assessment/currentPage',
            currentPageScore: 'assessment/currentPageScore'
        })
    },

    watch: {
        currentPage: {
            deep: true,

            handler () {
                if (!isEmpty(this.currentPage)) {
                    this.getCurrentPageScore()
                }
            }
        }
    },

    methods: {
        ...mapActions({
            getCurrentPageScore: 'assessment/getCurrentPageScore'
        })
    }
}
</script>