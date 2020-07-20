<template>
    <div class="py-16">
        <h1 class="text-3xl font-bold mb-6">
            {{ assessment.name }}
        </h1>

        <p class="mb-6">
            {{ assessment.description }}
        </p>

        <div class="flex justify-end">
            <button class="btn btn-blue">
                Start assessment
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        assessment: {
            type: Object,
            required: true
        }
    }
}
</script>