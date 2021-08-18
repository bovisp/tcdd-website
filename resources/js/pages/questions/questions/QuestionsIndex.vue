<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('generic.questions') }}
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
            :event-text="trans('generic.edit')"
            event="questions:edit"
        />

        <div 
            class="alert alert-blue"
            v-else
        >
            {{ trans('js_pages_questions_questions_questionsindex.noquestions') }}
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
                { field: 'categoryName', title: this.trans('generic.category'), sortable: true },
                { field: 'sectionName', title: this.trans('generic.section'), sortable: true },
                { field: 'name', title: this.trans('generic.name'), sortable: true }
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
    }
}
</script>