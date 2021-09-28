<template>
    <div>
        <form>
            
            <edit-content />

            <div class="flex">
                <b-button 
                    size="is-small"
                    type="is-info"
                    @click.prevent="store('content')"
                >
                    Create
                </b-button>
                
                <b-button 
                    size=" is-small"
                    type="is-text"
                    class="ml-auto"
                    @click.prevent="cancel"
                >
                    {{ trans('generic.cancel') }}
                </b-button>
            </div>
        </form>
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