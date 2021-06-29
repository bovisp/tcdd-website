<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            {{ trans('js_pages_admin_portal_categories_components_destroyportalcategory.deletecategory') }}
        </button>

        <modal 
            v-show="modalActive"
            ok-button-text="Delete"
            cancel-button-text="Cancel"
            @close="close"
            @submit="destroy"
        >
            <template slot="header">
                {{ trans('js_pages_admin_portal_categories_components_destroyportalcategory.deletecategory') }}: {{ category.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        {{ trans('js_pages_admin_portal_categories_components_destroyportalcategory.deletecategoryconfirm1') }}
                        <strong>{{ trans('js_pages_admin_portal_categories_components_destroyportalcategory.deletecategoryconfirm2') }}</strong>.
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
            category: 'portalCategories/category'
        })
    },

    methods: {
        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/admin/portal/categories/${this.category.id}`)

            this.close()

            this.$emit('close')
        }
    },
}
</script>