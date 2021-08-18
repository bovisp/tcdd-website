<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_questions_categories_questioncategoriesindex.questioncategories') }}
        </h1> 

        <datatable 
            v-if="typeof questionCategories !== 'undefined' && questionCategories.length"
            :data="questionCategories"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-event="true"
            :event-text="trans('generic.edit')"
            event="question-categories:edit"
        />

        <div 
            class="alert alert-blue"
            v-else
        >
            {{ trans('js_pages_questions_categories_questioncategoriesindex.nocategories') }}
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'name', title: this.trans('generic.name'), sortable: true },
            ]
        }
    },

    computed: {
        ...mapGetters({
            questionCategories: 'questionCategories/questionCategories'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'questionCategories/fetch',
            setEdit: 'questionCategories/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('question-categories:edit', questionCategory => {
            this.setEdit(questionCategory)
        })
    }
}
</script>