<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            {{ trans('js_pages_permissions_components_destroypermission.deletepermission') }}
        </button>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
            ok-button-text="Submit"
            cancel-button-text="Cancel"
        >
            <template slot="header">
                {{ trans('js_pages_permissions_components_destroypermission.deletepermission') }}: {{ permission.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        {{ trans('js_pages_permissions_components_destroypermission.deletepermissionconfirm1') }}
                        <strong>{{ trans('js_pages_permissions_components_destroypermission.deletepermissionconfirm2') }}</strong>.
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
            permission: 'permissions/permission'
        })
    },

    methods: {
        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/permissions/${this.permission.id}`)

            this.close()

            this.$emit('close')
        }
    },
}
</script>