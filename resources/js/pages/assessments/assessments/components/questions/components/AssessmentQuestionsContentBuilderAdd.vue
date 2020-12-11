<template>
    <div>
        <template v-if="data">
            <div class="mb-6">
                <assessment-page-content-builder 
                    :content-builder-id="data.contentBuilderItemEn.content.id"
                    lang="en"
                />
            </div>
            
            <div>
                <assessment-page-content-builder 
                    :content-builder-id="data.contentBuilderItemFr.content.id"
                    lang="fr"
                />
            </div>
        </template>
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

    data () {
        return {
            data: null
        }
    },

    async mounted () {
        let { data } = await axios.post(`${this.urlBase}/api/assessments/page/${this.page.id}/content`)

        this.data = data
    }
}
</script>