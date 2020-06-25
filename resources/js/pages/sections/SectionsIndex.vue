<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Sections
        </h1> 

        <datatable 
            :data="sections"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="sections:edit"
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
            sections: 'sections/sections'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'sections/fetch',
            setEdit: 'sections/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('sections:edit', section => {
            this.setEdit(section)
        })
    }
}
</script>