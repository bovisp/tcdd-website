<template>
    <div class="columns is-centered">
        <div class="column is-two-thirds">
            <assessment-participants-index 
                v-if="showIndex"
                @create="showIndex = false"
            />

            <assessment-participants-create 
                v-else
                @cancel="showIndex = true"
            />
        </div>
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