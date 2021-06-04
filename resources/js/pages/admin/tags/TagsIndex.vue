<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_admin_tags_tagsindex.tags') }}
        </h1> 

        <datatable 
            :data="tags"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-event="true"
            :event-text="trans('js_pages_admin_tags_tagsindex.tags')"
            event="tags:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'name', title: this.trans('js_pages_admin_tags_tagsindex.name'), sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            tags: 'tags/tags'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'tags/fetch',
            setEdit: 'tags/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('tags:edit', tag => {
            this.setEdit(tag)
        })
    }
}
</script>