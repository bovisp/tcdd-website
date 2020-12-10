<template>
    <div class="mt-6">
        <div class="flex justify-end mb-6">
            <button 
                class="btn btn-text text-sm btn-sm"
                @click.prevent="$emit('content-add:cancel')"
            >
                Cancel
            </button>
        </div>

        <template v-if="type === 'question'">
            <p>question</p>
        </template>

        <template v-if="type === 'content'">
            <assessment-questions-content-builder-add 
                :page="page"
            />
        </template>

        <div class="flex justify-end mt-6">
            <button 
                class="btn btn-text text-sm btn-sm"
                @click.prevent="$emit('content-add:cancel')"
            >
                Cancel
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: true
        },
        page: {
            type: Object,
            required: true
        }
    }
}
</script>