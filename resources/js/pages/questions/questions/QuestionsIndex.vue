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
import { find } from 'lodash-es'

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

        let params = (new URL(document.location)).searchParams;

        if (params.get('question')) {
            let question = await find(this.questions, q => q.id === parseInt(params.get('question')))

            await this.setEdit(question)

            window.events.$emit('questions:edit', question)
        }

        window.events.$on('questions:edit', question => {
                this.setEdit(question)
        })
    }
}
</script>