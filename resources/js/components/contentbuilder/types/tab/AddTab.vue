<template>
    <div>
        <form>
            <edit-tabs 
                :id="currentContentBuilder.id"
                @tabs:toggle-save-button="showCreateButton = !showCreateButton"
            />

            <store-buttons 
                :show-create-button="showCreateButton"
                @store="store({
                    type: 'tab',
                    id: currentContentBuilder.id,
                    isTabSectionPart
                })"
                @cancel="cancelAddingTab({
                    id: currentContentBuilder.id
                })"
            />
        </form>
    </div>
</template>

<script>
import storeContentBuilder from '../../../../mixins/storeContentBuilder'
import { forIn } from 'lodash-es'
import { mapActions } from 'vuex'

export default {
    mixins: [
        storeContentBuilder
    ],

    data () {
        return {
            showCreateButton: false
        }
    },

    watch: {
        errors: {
            deep: true,

            handler () {
                forIn(this.errors, (value, key) => {
                    if (key.includes('tabs')) {
                        this.$buefy.dialog.alert({
                            title: 'Error',
                            message: value[0],
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
            cancelAddingTab: 'contentbuilder/cancelAddingTab'
        }),

        store (payload) {
            this.createPart(payload)
        }
    },

    mounted () {
        window.events.$emit('tabs:toggle-save-button')
    }
}
</script>