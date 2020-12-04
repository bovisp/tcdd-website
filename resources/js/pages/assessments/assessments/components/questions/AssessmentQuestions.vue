<template>
    <div>
        <assessment-questions-index 
            v-if="showIndex"
        />

        <assessment-questions-create 
            v-else
        />
    </div>
</template>

<script>
export default {
    data () {
        return {
            showIndex: true
        }
    }
}
</script>