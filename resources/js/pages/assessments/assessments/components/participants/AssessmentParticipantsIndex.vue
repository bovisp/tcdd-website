<template>
    <div>
        <nav class="level">
            <div class="level-left"></div>

            <div class="level-right">
                <div class="level-item">
                    <b-button 
                        @click.prevent="$emit('create')"
                        type="is-text is-small"
                    >{{ trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.addmoreparticipants') }}</b-button>
                </div>
            </div>
        </nav>

        <b-table 
            :data="assessment.participants" 
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
                        title: 'Remove participant',
                        message: 'Are you sure you want to <b>remove</b> this participant?',
                        confirmText: 'Remove',
                        type: 'is-danger',
                        hasIcon: true,
                        onConfirm: () => destroy(props.row.pivot)
                    })"
                >Remove</b-button>
            </b-table-column>

            <template #empty>
                <b-message type="is-info">
                    There are no participants associated with this assessment.
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
            removeParticipant: 'assessments/removeParticipant'
        }),

        async destroy (participant) {
            let data = await this.removeParticipant(participant)

            this.$buefy.toast.open({
                message: data.data.message,
                type: 'is-success'
            })
        }
    }
}
</script>