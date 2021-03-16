<template>
    <div class="w-full">
        <div class="flex items-center w-full">
            <h2 class="text-3xl font-medium mb-2">
                Review: {{ attemptId }}
            </h2>

            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                Return to profile
            </button>
        </div>

        <div class="flex items-center w-full">
            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                Return to profile
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        attemptId: {
            type: Number,
            required: true
        }
    },

    methods: {
        cancel () {
            this.$emit('assessment:review-cancel')
        }
    }
}
</script>