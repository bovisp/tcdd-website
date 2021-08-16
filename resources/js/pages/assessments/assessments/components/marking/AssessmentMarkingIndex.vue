<template>
    <div class="flex justify-center">
        <div class="w-full lg:w-2/3">
        <b-table 
            :data="attemptAnswers" 
            :default-sort="['participant_lastname']"
        >
            <b-table-column 
                field="participant_firstname" 
                :label="trans('generic.firstname')" 
                v-slot="props"
            >
                {{ props.row.participant_firstname }}
            </b-table-column>

            <b-table-column 
                field="participant_firstname" 
                :label="trans('generic.lastname')" 
                v-slot="props"
            >
                {{ props.row.participant_lastname }}
            </b-table-column>

            <b-table-column 
                field="marked_on" 
                :label="trans('generic.markingcompleted')" 
                v-slot="props"
            >
                <span v-if="props.row.marked_on !== 'No'">{{ dayjs(props.row.marked_on).format('YYYY-MM-DD') }}</span>

                <span v-else>No</span>
            </b-table-column>

            <b-table-column 
                 v-slot="props"
            >
                <b-button
                    type="is-text"
                    class="is-small has-text-info"
                    @click.prevent="mark(props.row)"
                >{{ trans('generic.mark') }}</b-button>
            </b-table-column>
        </b-table>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import dayjs from 'dayjs'

export default {
    computed: {
        ...mapGetters({
            attemptAnswers: 'assessments/attemptAnswers'
        })
    },

    methods: {
        ...mapActions({
            fetchParticipantAnswers: 'assessments/fetchParticipantAnswers',
            setParticipantAnswer: 'assessments/setParticipantAnswer'
        }),

        dayjs,

        mark (e) {
            window.events.$emit('assessment:mark', e)
        }
    },

    async mounted () {
        window.events.$on('assessment:mark', attempt => {
            this.setParticipantAnswer(attempt)

            window.events.$emit('assessment:marking')
        })
    }
}
</script>