<template>
    <div>

        <button 
            class="btn btn-text btn-sm text-sm"
            @click.prevent="$emit('question-duplicate:cancel')"
        >
            Cancel
        </button>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    methods: {
        ...mapActions({
            duplicateQuestion: 'questions/duplicateQuestion'
        })
    },

    mounted () {
        window.events.$on('duplicate-question:duplicate', async () => {
            await this.duplicateQuestion()
        })
    }
}
</script>