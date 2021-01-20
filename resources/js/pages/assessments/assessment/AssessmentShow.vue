<template>
    <div>
        {{ attempt }}
    </div>
</template>

<script>
export default {
    props: {
        attempt: {
            type: Object,
            required: true
        }
    }
}
</script>