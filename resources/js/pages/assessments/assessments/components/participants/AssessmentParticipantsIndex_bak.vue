<template>
    <div>
        <div class="mx-auto w-full lg:w-1/2 mb-4">
            <div class="flex justify-end">
                <button 
                    class="btn btn-blue btn-sm text-sm"
                    @click.prevent="update"
                    v-if="typeof assessment.participants !== 'undefined' && assessment.participants.length"
                >
                    Update participants
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm"
                    :class="{ 
                        'ml-2' : typeof assessment.participants !== 'undefined' && assessment.participants.length,
                        'ml-auto' : typeof assessment.participants === 'undefined'
                    }"
                    @click.prevent="$emit('create')"
                >
                    {{ trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.addmoreparticipants') }}
                </button>
            </div>
        </div>

        <div class="flex justify-center">
            <div 
                class="w-full lg:w-1/2"
                v-if="typeof assessment.participants !== 'undefined' && assessment.participants.length"
            >
                <datatable 
                    :data="assessment.participants"
                    :columns="columns"
                    :selected-items="selectedUsers"
                    :per-page="10"
                    :order-keys="['lastname', 'firstname']"
                    :order-key-directions="['asc', 'asc']"
                    :has-text-filter="false"
                    :checkable="true"
                    :has-event="true"
                    event-text-boolean="pivot.activated"
                    :event-text-true="trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.deactivate')"
                    :event-text-false="trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.activate')"
                    event="assessment:activate"
                    no-event-if-text-column-boolean="completed"
                    text-column-text="Completed"
                    key="participants"
                ></datatable>
            </div>

            <div 
                class="alert alert-blue w-full lg:w-1/2"
                v-else
            >
                {{ trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.nousers') }}
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { map, intersection } from 'lodash-es'

export default {
    data () {
        return {
            selected: [],
            columns: [
                { field: 'firstname', title: this.trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.firstname'), sortable: true },
                { field: 'lastname', title: this.trans('js_pages_assessments_assessments_components_participants_assessmentparticipantsindex.lastname'), sortable: true },
            ]
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        }),

        selectedUsers () {
            return map(this.assessment.participants, user => user.id)
        }
    },

    watch: {
        assessment: {
            deep: true,

            handler () {
                this.selected = this.selectedUsers

                window.events.$emit('datatable:reload-selected', this.selected)
            }
        }
    },

    methods: {
        ...mapActions({
            fetchAssessment: 'assessments/fetchAssessment',
            activateParticipant: 'assessments/activateParticipant'
        }),

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/assessments/${this.assessment.id}/participants`, {
                users: this.selected
            })

            await this.reload()

            window.events.$emit('datatable:reload-selected', map(
                this.assessment.participants, participant => participant.id
            ))

            this.$toasted.success(data.data.message)
        },

        async reload () {
            await this.fetchAssessment(this.assessment.id)
        }
    },

    async mounted () {
        this.selected = this.selectedUsers

        window.events.$on('users:selected', selectedUsers => {
            this.selected = intersection(selectedUsers, this.selectedUsers)
        })

        window.events.$on('assessments:reload', async () => {
            await this.reload()
        })

        window.events.$on('assessment:activate', async (participant) => {
            await this.activateParticipant({
                participantId: participant.pivot.id,
                isActivated: participant.pivot.activated
            })

            // this.$toasted.success(`${participant.fullname} has been successfully ${participant.pivot.activated ? 'deactivated' : 'activated'}.`)
        })
    }
}
</script>