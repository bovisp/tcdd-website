<template>
    <div
        class="absolute w-full h-full bg-gray-900 opacity-50"
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