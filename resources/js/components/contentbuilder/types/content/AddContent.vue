<template>
    <div>
        <edit-content />

        <store-buttons 
            @store="store('content')"
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

    data () {
        return {
            form: {
                content: '',
                content_builder_type_id: 2,
                is_tab_section: this.isTabSectionPart
            }
        }
    },

    methods: {
        cancel () {
            this.form.content = ''

            this.genericCancel()
        }
    },

    mounted () {
        window.events.$on('content:update-form', content => this.form.content = content)
    }
}
</script>