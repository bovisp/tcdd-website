<template>
    <div>
        content builder
    </div>
</template>

<script>
export default {
    props: {
        page: {
            type: Object,
            required: true
        }
    },

    async mounted () {
        let { data } = await axios.post(`${this.urlBase}/api/assessments/page/${this.page.id}/content`)

        console.log(data)
    }
}
</script>