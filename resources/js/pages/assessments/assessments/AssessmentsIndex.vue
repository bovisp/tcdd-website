<template>
    <div>
        <h1 class="title">
            {{ trans('generic.assessments') }}
        </h1> 

        <b-table 
            :data="assessments" 
            :default-sort="['typeName', 'name', 'section']"
        >
            <b-table-column 
                field="typeName" 
                :label="trans('generic.type')" 
                v-slot="props"
            >
                {{ props.row.typeName }}
            </b-table-column>

            <b-table-column 
                field="name" 
                :label="trans('generic.name')" 
                v-slot="props"
            >
                {{ props.row.name }}
            </b-table-column>

            <b-table-column 
                field="sectionName" 
                :label="trans('generic.section')" 
                v-slot="props"
            >
                {{ props.row.sectionName }}
            </b-table-column>

            <b-table-column 
                v-slot="props"
            >
                <b-button
                    type="is-text is-small"
                    @click.prevent="edit(props.row)"
                >{{ trans('generic.edit') }}</b-button>
            </b-table-column>

            <template #empty>
                <b-message type="is-info">
                    You have no assessments linked to you.
                </b-message>
            </template>
        </b-table>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            assessments: 'assessments/assessments'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'assessments/fetch',
            setEdit: 'assessments/setEdit'
        }),

        edit (assessment) {
            this.setEdit(assessment)

            window.events.$emit('assessments:edit')
        }
    },
    
    async mounted () {
        await this.fetch()

        // window.events.$on('assessments:edit', assessment => {
        //     this.setEdit(assessment)
        // })
    }
}
</script>