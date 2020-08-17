<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Questions
        </h1> 

        <datatable 
            v-if="typeof questions !== 'undefined' && questions.length"
            :data="questions"
            :columns="columns"
            :per-page="10"
            :order-keys="['categoryName', 'sectionName', 'name']"
            :order-key-directions="['asc', 'asc', 'asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="Edit"
            event="questions:edit"
        />

        <div 
            class="alert alert-blue"
            v-else
        >
            No questions have been created.
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'categoryName', title: 'Category', sortable: true },
                { field: 'sectionName', title: 'Section', sortable: true },
                { field: 'name', title: 'Name', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            questions: 'questions/questions'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'questions/fetch',
            setEdit: 'questions/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('questions:edit', question => {
            this.setEdit(question)
        })
    }
}
</script>