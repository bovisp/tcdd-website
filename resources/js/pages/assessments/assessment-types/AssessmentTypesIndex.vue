<template>
    <div>
        <h1 class="title">
            {{ trans('js_pages_assessments_assessment-types_assessmenttypesindex.assessmenttypes') }}
        </h1> 

        <b-table 
            :data="types" 
            :default-sort="['name']"
        >
            <b-table-column 
                field="name" 
                :label="trans('js_pages_assessments_assessment-types_assessmenttypesindex.name')" 
                v-slot="props"
            >
                {{ props.row.name }}
            </b-table-column>

            <b-table-column 
                v-slot="props"
            >
                <b-button
                    type="is-text is-small"
                    @click.prevent="edit(props.row)"
                >{{ trans('generic.edit') }}</b-button>
            </b-table-column>
        </b-table>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            types: 'assessmentTypes/assessmentTypes'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'assessmentTypes/fetch',
            setEdit: 'assessmentTypes/setEdit'
        }),

        edit (type) {
            this.setEdit(type)

            window.events.$emit('assessment-types:edit')
        }
    },
    
    async mounted () {
        await this.fetch()
    }
}
</script>