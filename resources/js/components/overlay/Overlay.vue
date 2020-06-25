<template>
    <div
        class="fixed bg-gray-400 inset-0 min-w-full min-h-screen z-10"
        @click="close"
    ></div>
</template>

<script>
export default {
    methods: {
        close () {
            this.$emit('close')
        }
    },
}
</script>