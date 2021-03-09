<template>
    <div>
        <template v-if="!editing">
            {{ mark ? markScore : 'N/A' }}

            <i 
                class="fas fa-edit text-gray-600 cursor-pointer ml-1"
                v-if="markScore >= 0"
                :title="`Edit question ${question.question_number} score for ${attempt.participant_fullname}`"
                @click.prevent="editing = true"
            ></i>
        </template>

        <template v-else>
            <input 
                type="number"
                v-model="markScore"
                class="shadow appearance-none border rounded w-16 py-1 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-1 text-sm"
            >

            <i 
                class="fas fa-save text-gray-600 cursor-pointer mr-1"
                @click.prevent="update"
            ></i>

            <i 
                class="fas fa-times text-gray-600 cursor-pointer"
                @click.prevent="editing = false"
            ></i>

            <span class="block mt-1 text-left text-xs">
                <strong>Max: </strong>
                {{ question.question_score }}
            </span>
        </template>
    </div>
</template>

<script>
import { find } from 'lodash-es'
import { mapActions } from 'vuex'

export default {
    props: {
        attempt: {
            type: Object,
            required: true
        },
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            editing: false,
            mark: null,
            markScore: null
        }
    },

    methods: {
        ...mapActions({
            updateMarkScore: 'assessments/updateMarkScore'
        }),

        async update () {
            await this.updateMarkScore({
                attempt: this.attempt,
                mark: this.mark,
                score: this.markScore
            })

            this.editing = false

            window.events.$emit('assessment:results-mark-table', {
                attempt: this.attempt,
                mark: this.mark,
                score: parseFloat(this.markScore)
            })
        }
    },

    mounted () {
        this.mark = find(this.attempt.marks, ['question_id', this.question.id])

        if (this.mark) {
            this.markScore = parseFloat(this.mark.mark)
        }   
        
        window.events.$on('assessment:mark-update-attempt', payload => {
            if (this.attempt && this.question) {
                if (payload.data.question_id === this.question.id && this.attempt.id === payload.data.assessment_attempt_id) {
                    this.mark = payload.data
                    this.markScore = parseFloat(payload.data.mark)
                }
            }
        })
    }
}
</script>