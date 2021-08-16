<template>
    <div>
        <nav class="level">
            <div class="level-left"></div>

            <div class="level-right">
                <div class="level-item">
                    <b-button 
                        @click.prevent="$emit('cancel')"
                        type="is-text is-small"
                    >{{ trans('generic.cancel') }}</b-button>
                </div>

                <div class="level-item">
                    <b-button 
                        type="is-small is-info"
                        @click.prevent="store"
                    >{{ trans('js_pages_assessments_assessments_components_participants_assessmentparticipantscreate.addparticipants') }}</b-button>
                </div>
            </div>
        </nav>   

        <b-table 
            :data="users" 
            :columns="columns"
            :default-sort="['lastname']"
            :checked-rows.sync="selected"
            :paginated="true"
            :per-page="10"
            checkable
        >
            <template #empty>
                <b-message type="is-info">
                    {{ trans('js_pages_assessments_assessments_components_participants_assessmentparticipantscreate.nousers') }}
                </b-message>
            </template>
        </b-table>    
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            users: [],
            selected: [],
            columns: [
                { field: 'firstname', label: this.trans('generic.firstname') },
                { field: 'lastname', label: this.trans('generic.lastname'), searchable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            addParticipants: 'assessments/addParticipants'
        }),

        async store () {
            let { data } = await this.addParticipants(this.selected)

            this.selected = []

            this.$buefy.toast.open({
                message: data.message,
                type: 'is-success'
            })

            this.$emit('cancel')
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/assessments/${this.assessment.id}/participants/create`)

        this.users = users
    }
}
</script>