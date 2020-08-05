<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Assessment types
        </h1> 

        <datatable 
            :data="types"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="assessment-types:edit"
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
            types: 'assessmentTypes/assessmentTypes'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'assessmentTypes/fetch',
            setEdit: 'assessmentTypes/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('assessment-types:edit', type => {
            this.setEdit(type)
        })
    }
}
</script>