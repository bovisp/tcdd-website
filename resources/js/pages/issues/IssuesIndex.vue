<template>
    <div class="w-full mt-8">
        <h1 class="text-3xl font-bold mb-4">
            Issues
        </h1> 

        <datatable 
            :data="issues"
            :columns="columns"
            :per-page="10"
            :order-keys="['code']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="Edit"
            event="issues:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'code', title: 'Code', sortable: true },
                { field: 'title', title: 'Title', sortable: true },
                { field: 'status', title: 'Status', sortable: true },
                { field: 'closed_at', title: 'Closed', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            issues: 'issues/issues'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'issues/fetch',
            setEdit: 'issues/setEdit'
        })
    },

    async mounted () {
        await this.fetch()

        window.events.$on('issues:edit', issue => {
            this.setEdit(issue)
        })
    }
}
</script>