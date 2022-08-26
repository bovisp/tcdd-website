<template>
    <div>
        <nav class="level">
            <div class="level-left"></div>

            <div class="level-right">
                <div class="level-item">
                    <b-button 
                        @click.prevent="$emit('create')"
                        type="is-text is-small"
                    >{{ trans('js_pages_assessments_assessments_components_instructors_assessmentinstructorsindex.addmoreinstructors') }}</b-button>
                </div>
            </div>
        </nav>

        <b-table 
            :data="assessment.editors" 
            :default-sort="['lastname']"
        >
            <b-table-column 
                field="firstname" 
                :label="trans('generic.firstname')" 
                v-slot="props"
            >
                {{ props.row.firstname }}
            </b-table-column>

            <b-table-column 
                field="lastname" 
                :label="trans('generic.lastname')" 
                v-slot="props"
            >
                {{ props.row.lastname }}
            </b-table-column>

            <b-table-column 
                 v-slot="props"
            >
                <b-button
                    type="is-text"
                    class="is-small has-text-danger"
                    @click.prevent="$buefy.dialog.confirm({
                        title: trans('js_pages_assessments_assessments_components_instructors_assessmentinstructorsindex.removeinstructor'),
                        message: trans('js_pages_assessments_assessments_components_instructors_assessmentinstructorsindex.removeinstructorconfirm'),
                        confirmText: trans('generic.remove'),
                        type: 'is-danger',
                        hasIcon: true,
                        onConfirm: () => destroy(props.row.pivot)
                    })"
                >{{ trans('generic.remove') }}</b-button>
            </b-table-column>

            <template #empty>
                <b-message type="is-info">
                    {{ trans('js_pages_assessments_assessments_components_instructors_assessmentinstructorsindex.nousers') }}.
                </b-message>
            </template>
        </b-table>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            removeInstructor: 'assessments/removeInstructor'
        }),

        async destroy (instructor) {
            let data = await this.removeInstructor(instructor)

            this.$buefy.toast.open({
                message: data.data.message,
                type: 'is-success'
            })
        }
    }
}
</script>