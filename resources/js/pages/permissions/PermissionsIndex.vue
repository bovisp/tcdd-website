<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('generic.permissions') }}
        </h1> 

        <datatable 
            :data="permissions"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            :event-text="trans('generic.edit')"
            event="permissions:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'name', title: this.trans('generic.name'), sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            permissions: 'permissions/permissions'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'permissions/fetch',
            setEdit: 'permissions/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('permissions:edit', permission => {
            this.setEdit(permission)
        })
    }
}
</script>