<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            {{ trans('js_pages_admin_tags_components_destroytag.deletetag') }}
        </button>

        <modal 
            v-show="modalActive"
            ok-button-text="Delete"
            cancel-button-text="Cancel"
            @close="close"
            @submit="destroy"
        >
            <template slot="header">
                {{ trans('js_pages_admin_tags_components_destroytag.deletetag') }}: {{ tag.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        {{ trans('js_pages_admin_tags_components_destroytag.deletetagconfirm1') }}
                        <strong>{{ trans('js_pages_admin_tags_components_destroytag.deletetagconfirm2') }}</strong>.
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            tag: 'tags/tag'
        })
    },

    methods: {
        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/admin/tags/${this.tag.id}`)

            this.close()

            this.$emit('close')
        }
    },
}
</script>