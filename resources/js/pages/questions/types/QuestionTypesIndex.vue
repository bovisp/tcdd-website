<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_questions_types_questiontypesindex.questiontypes') }}
        </h1> 

        <datatable 
            v-if="typeof questionTypes !== 'undefined' && questionTypes.length"
            :data="questionTypes"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-event="true"
            :event-text="trans('generic.edit')"
            event="question-types:edit"
        />

        <div 
            class="alert alert-blue"
            v-else
        >
            {{ trans('js_pages_questions_types_questiontypesindex.noquestiontypes') }}
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
            questionTypes: 'questionTypes/questionTypes'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'questionTypes/fetch',
            setEdit: 'questionTypes/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('question-types:edit', questionTypes => {
            this.setEdit(questionTypes)
        })
    }
}
</script>