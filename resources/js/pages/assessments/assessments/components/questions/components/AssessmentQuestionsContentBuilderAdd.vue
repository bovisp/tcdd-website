<template>
    <div>
        <template v-if="data">
            <div class="mb-6">
                <content-builder 
                    :id="data.contentBuilderItemEn.content.id"
                />
            </div>
            
            <div>
                <content-builder 
                    :id="data.contentBuilderItemFr.content.id"
                />
            </div>
        </template>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    data () {
        return {
            data: null
        }
    },

    methods: {
        ...mapActions({
            addContentToPage: 'assessments/addContentToPage'
        })
    },

    async mounted () {
        let { data } = await this.addContentToPage()

        this.data = data

        window.events.$emit('assessment-page:content-builder-data', this.data)
    }
}
</script>