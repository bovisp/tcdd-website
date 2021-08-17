<template>
    <div class="w-full mt-8">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('generic.issues') }}
        </h1> 

        <datatable 
            :data="issues"
            :columns="columns"
            :per-page="10"
            :order-keys="['code']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-event="true"
            :event-text="trans('generic.edit')"
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
                { field: 'code', title: this.trans('js_pages_issues_issuesindex.code'), sortable: true },
                { field: 'title', title: this.trans('generic.title'), sortable: true },
                { field: 'status', title: this.trans('js_pages_issues_issuesindex.status'), sortable: true },
                { field: 'closed_at', title: this.trans('js_pages_issues_issuesindex.closed'), sortable: true }
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