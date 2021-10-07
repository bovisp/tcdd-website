<template>
    <div>
        <edit-content 
            :id="currentContentBuilder.id"
            :is-tab-section-part="isTabSectionPart"
        />

        <store-buttons 
            @store="store({
                type: 'content',
                id: currentContentBuilder.id,
                isTabSectionPart
            })"
            @cancel="cancel"
        />
    </div>
</template>

<script>
import storeContentBuilder from '../../../../mixins/storeContentBuilder'

export default {
    mixins: [
        storeContentBuilder
    ],

    methods: {
        cancel () {
            this.genericCancel()

            window.events.$emit('tabs:cancel-add-part')
        },

        store (payload) {
            this.createPart(payload)
        }
    }
}
</script>