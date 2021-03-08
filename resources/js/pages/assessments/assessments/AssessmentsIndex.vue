<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Assessments
        </h1> 

        <datatable 
            v-if="typeof assessments !== 'undefined' && assessments.length"
            :data="assessments"
            :columns="columns"
            :per-page="10"
            :order-keys="['typeName', 'name', 'section']"
            :order-key-directions="['asc', 'asc', 'asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="Edit"
            event="assessments:edit"
        />

        <div 
            class="alert alert-blue"
            v-else
        >
            No assessments have been created.
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'typeName', title: 'Type', sortable: true },
                { field: 'name', title: 'Name', sortable: true },
                { field: 'sectionName', title: 'Section', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            assessments: 'assessments/assessments'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'assessments/fetch',
            setEdit: 'assessments/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('assessments:edit', assessment => {
            this.setEdit(assessment)
        })
    }
}
</script>