<template>
    <div
        class="w-full my-4"
    >
        <label 
            class="block text-gray-700 font-bold mb-2" 
            for="comment"
        >
            {{ trans('generic.comment') }}
        </label>

        <vue-editor 
            v-model="comment"
        ></vue-editor>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { VueEditor, Quill } from 'vue2-editor'
import { debounce, find } from 'lodash-es'

export default {
    components: {
        VueEditor
    },

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
           comment: null,
           mark: null
        }
    },

    computed: {
        ...mapGetters({
            participantAnswer: 'assessments/participantAnswer'
        })
    },

    watch: {
        comment: {
            handler: debounce(function (data) {
                if (!this.comment && typeof this.mark === 'undefined') {
                    return 
                }

                this.updateMark({
                    id: this.mark ? this.mark.id : null,
                    questionId: this.question.id,
                    itemId: this.question.question_item,
                    comment: this.comment,
                    mark: this.mark ? this.mark.mark : null,
                    attemptId: this.answer ? this.answer.id : null
                })
            }, 500)
        }
    },

    methods: {
        ...mapActions({
            updateMark: 'assessments/updateMark'
        })
    },

    mounted () {
        if (this.answer === null) {
            this.mark = find(this.participantAnswer.marks, mark => mark.question_id === this.question.id)
        } else {
            this.mark = find(this.answer.marks, mark => mark.question_id === this.question.id)
        }

        if (this.mark) {
            this.comment = this.mark.description
        }

        window.events.$on('assessment:mark-update', mark => {
            if (mark.question_id === this.question.id) {
                this.mark = mark
                this.comment = mark.description
            }
        })

        window.events.$on('assessment:mark-update-attempt', payload => {
            if (this.answer && this.question) {
                if (payload.data.question_id === this.question.id && this.answer.id === payload.attemptId) {
                    this.mark = payload.data
                    this.comment = payload.data.description
                }
            }
        })
    }
}
</script>