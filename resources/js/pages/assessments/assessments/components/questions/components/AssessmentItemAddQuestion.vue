<template>
    <div v-if="availableQuestions">
        <b-table
            v-if="!selectedQuestion.questionId"
            :data="availableQuestions"
            :default-sort="['categoryName']"
            paginated
            per-page="5"
        >
            <b-table-column 
                field="name" 
                :label="trans('generic.name')" 
                v-slot="props"
            >
                {{ props.row.name }}
            </b-table-column>

            <b-table-column 
                field="categoryName" 
                :label="trans('generic.category')" 
                v-slot="props"
                :searchable="true"
            >
                {{ props.row.categoryName }}
            </b-table-column>

            <b-table-column 
                v-slot="props"
            >
                <b-button
                    @click.prevent="addQuestion(props.row.id)"
                    type="is-text"
                    class="has-text-info"
                >
                    {{ trans('generic.add') }}
                </b-button>
            </b-table-column>
        </b-table>

        <div
            v-else
            class="level"
        >
            <div class="level-left">
                <div class="level-item">
                    <div>
                        <b-input 
                            v-model="selectedQuestion.score"
                            :placeholder="`${trans('generic.score')}...`"
                        ></b-input>

                        <p
                            v-if="validationMessage"
                            v-text="validationMessage"
                            class="has-text-danger is-size-7 block"
                        ></p>
                    </div>
                </div>

                <div class="level-item">
                    <b-button
                        type="is-info"
                        @click.prevent="add"
                    >
                        {{ sentenceCase(`${trans('generic.add')} ${trans('generic.question')}`) }}
                    </b-button>
                </div>
            </div>

            <div class="level-right"></div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { sentenceCase } from 'change-case'

export default {
    data () {
        return {
            selectedQuestion: {
                questionId: null,
                score: null
            },
            validationMessage: ''
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment',
            availableQuestions: 'assessments/availableQuestions'
        })
    },

    methods: {
        ...mapActions({
            fetchAvailableQuestions: 'assessments/fetchAvailableQuestions',
            addQuestionToPage: 'assessments/addQuestionToPage'
        }),
        
        sentenceCase,

        addQuestion (questionId) {
            this.selectedQuestion.questionId = questionId
        },

        add () {
            if (this.selectedQuestion.score > 0) {
                this.$emit('assessment:question-add', this.selectedQuestion)

                return
            }

            this.validationMessage = 'The score must be greater than 0.'
        }
    },

    async mounted () {
        await this.fetchAvailableQuestions()
    }
}
</script>