<template>
    <div
        class="my-4"
    >
        <label 
            class="block text-gray-700 font-bold mb-2" 
            :class="{ 'text-red-500': errors.mark }"
            for="score"
        >
            {{ trans('generic.score') }}
        </label>

        <div class="flex">
            <template v-if="scoring || !hasExistingScore">
                <input 
                    type="number" 
                    v-model="score"
                    class="shadow appearance-none border rounded w-32 py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2 text-sm"
                    id="score"
                    :class="{ 'border-red-500': errors.mark }"
                >
            
                <button 
                    class="btn btn-blue btn-sm text-sm mr-2"
                    @click.prevent="update"
                >
                    {{ hasExistingScore ? trans('generic.update') : trans('generic.save') }}
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm"
                    v-if="hasExistingScore"
                    @click.prevent="scoring = false"
                >
                    {{ trans('generic.cancel') }}
                </button>
            </template>

            <div class="flex items-center">
                <template v-if="!scoring && hasExistingScore">
                    <span>{{ score }} {{ trans('generic.points') }}</span>
                
                    <button 
                        class="btn btn-text btn-sm text-sm ml-2"
                        @click.prevent="scoring = true"
                    >
                        {{ trans('generic.edit') }}
                    </button>
                </template>

                <span class="ml-2">
                    (<strong>{{ trans('generic.questionscore') }}: </strong>{{ question.question_score }} {{ trans('generic.points') }})
                </span>
            </div>
        </div>

        <p
            v-if="errors.mark"
            v-text="errors.mark[0]"
            class="text-red-500 text-sm"
        ></p>

        <p 
            if="hasMarker"
            class="mt-4"
        >
            <span v-if="hasMarker">
                <strong>{{ trans('generic.markedby') }}: </strong>{{ hasMarker ? mark.marked_by : '' }}
            </span>
        </p>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { find } from 'lodash-es'

export default {
    props: {
        question: {
            type: Object,
            required: true
        },
        answer: {
            type: Object,
            required: false,
            default: null
        }
    },

    data () {
        return {
            score: null,
            mark: null,
            scoring: false
        }
    },

    computed: {
        ...mapGetters({
            participantAnswer: 'assessments/participantAnswer',
            assessmentAttempts: 'assessments/participantAnswer',
            assessmentAnswers: 'assessments/attemptAnswers'
        }),

        hasExistingScore () {
            if (this.mark === null || typeof this.mark === 'undefined') {
                return false
            }

            if (this.mark.mark === null) {
                return false
            }

            return true
        },

        hasMarker () {
            if (this.mark === null || typeof this.mark === 'undefined') {
                return false
            }

            if (this.mark.marked_by === null) {
                return false
            }

            return true
        }
    },

    methods: {
        ...mapActions({
            updateMark: 'assessments/updateMark'
        }),

        async update () {
            if (!this.score && typeof this.mark === 'undefined') {
                return 
            }

            await this.updateMark({
                id: this.mark ? this.mark.id : null,
                questionId: this.question.id,
                itemId: this.question.question_item,
                comment: this.mark ? this.mark.description : null,
                mark: parseFloat(this.score),
                attemptId: this.answer ? this.answer.id : null
            })

            this.scoring = false

            window.events.$emit('assessment:results-mark-table', {
                attempt: this.participantAnswer,
                mark: this.mark,
                score: parseFloat(this.score),
                attempts: this.assessmentAnswers
            })
        }
    },

    mounted () {
        if (this.answer === null) {
            this.mark = find(this.participantAnswer.marks, mark => mark.question_id === this.question.id)
        } else {
            this.mark = find(this.answer.marks, mark => mark.question_id === this.question.id)
        }

        if (this.mark) {
            this.score = this.mark.mark
        }

        window.events.$on('assessment:mark-update', mark => {
            if (mark.question_id === this.question.id) {
                this.mark = mark
                this.score = mark.mark
            }
        })

        window.events.$on('assessment:mark-update-attempt', payload => {
            if (this.answer && this.question) {
                if (payload.data.question_id === this.question.id && this.answer.id === payload.attemptId) {
                    this.mark = payload.data
                    this.score = payload.data.mark
                }
            }
        })
    }
}
</script>