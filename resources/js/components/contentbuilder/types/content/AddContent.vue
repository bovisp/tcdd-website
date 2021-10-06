<template>
    <div>
        <edit-content 
            :id="currentContentBuilder.id"
        />

        <store-buttons 
            @store="store({
                type: 'content',
                id: currentContentBuilder.id
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
        },

        store (payload) {
            this.createPart(payload)
        }
    }
}
</script>