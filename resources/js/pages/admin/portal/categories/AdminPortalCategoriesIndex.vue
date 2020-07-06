<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Training Portal course categories
        </h1> 

        <datatable 
            :data="categories"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="Edit"
            event="portal-categories:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'name', title: 'Name', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            categories: 'portalCategories/categories'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'portalCategories/fetch',
            setEdit: 'portalCategories/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('portal-categories:edit', category => {
            this.setEdit(category)
        })
    }
}
</script>