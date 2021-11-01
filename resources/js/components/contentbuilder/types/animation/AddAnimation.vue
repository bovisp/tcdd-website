<template>
    <div class="w-full">
        <edit-animation 
            :id="currentContentBuilder.id"
            :is-tab-section-part="isTabSectionPart"
            :tab-part-data-id="tabPartDataId"
        />

        <store-buttons 
             @store="store({
                type: 'animation',
                id: currentContentBuilder.id,
                isTabSectionPart,
                tabPartDataId
            })"
            @cancel="cancel"
        />
    </div>
</template>

<script>
import storeContentBuilder from '../../../../mixins/storeContentBuilder'
import { mapActions } from 'vuex'
import { forIn } from 'lodash-es'

export default {
    mixins: [
        storeContentBuilder
    ],

    watch: {
        errors: {
            deep: true,

            handler () {
                forIn(this.errors, (value, key) => {
                    if (key.includes('filename')) {
                        this.$buefy.dialog.alert({
                            title: 'Error',
                            message: 'You need to add an animation first.',
                            type: 'is-danger',
                            ariaRole: 'alertdialog',
                            ariaModal: true
                        })
                    }
                })
            }
        }
    },

    methods: {
        ...mapActions({
            removeFile: 'contentbuilder/removeFile',
            updateNewForm: 'contentbuilder/updateNewForm'
        }),

        store (payload) {
            this.createPart(payload)
        },

        async cancel () {
            if (this.currentContentBuilder.new.files) {
                await this.removeFile(this.currentContentBuilder.new.files)
            }

            this.genericCancel()

            window.events.$emit('tabs:cancel-add-part')
        }
    }
}
</script>