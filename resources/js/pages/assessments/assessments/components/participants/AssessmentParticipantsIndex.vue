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
                    class="is-small"
                    v-if="!inExam(props.row.pivot.id)"
                    @click.prevent="changeActivationStatus(props.row.id)"
                >{{ status(props.row.id, props.row.pivot.id) }}</b-button>

                <span v-else>
                    {{ status(props.row.id, props.row.pivot.id) }}
                </span>
            </b-table-column>

            <b-table-column 
                 v-slot="props"
            >
                <b-button
                    type="is-text"
                    class="is-small has-text-danger"
                    :disabled="inExam(props.row.pivot.id)"
                    @click.prevent="$buefy.dialog.confirm({
                        title: trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.removeparticipant'),
                        message: trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.removeparticipantconfirm'),
                        confirmText: trans('generic.remove'),
                        type: 'is-danger',
                        hasIcon: true,
                        onConfirm: () => destroy(props.row.pivot)
                    })"
                >{{ trans('generic.remove') }}</b-button>
            </b-table-column>

            <template #empty>
                <b-message type="is-info">
                    {{ trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.nousers') }}.
                </b-message>
            </template>
        </b-table>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { find } from 'lodash-es'

export default {
    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            removeParticipant: 'assessments/removeParticipant',
            activateParticipant: 'assessments/activateParticipant'
        }),

        status (userId, participantId) {
            let participant = find(this.assessment.participants, user => user.id === userId)

            let attempt = find(this.assessment.attempts, attempt => attempt.assessment_participant_id === participantId)
  
            if (participant.pivot.activated && !attempt) {
                return this.trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.deactivate')
            }

            if (participant.pivot.activated && !attempt.completed) {
                return this.trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.inprogress')
            }

            if (attempt && attempt.completed) {
                return this.trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.completed')
            }

            return this.trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.activate')
        },

        inExam (participantId) {
            return find(this.assessment.attempts, attempt => attempt.assessment_participant_id === participantId)
        },

        async changeActivationStatus (userId) {
            await this.activateParticipant(userId)

            this.$buefy.toast.open({
                message: this.trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.activationupdated'),
                type: 'is-success'
            })
        },

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