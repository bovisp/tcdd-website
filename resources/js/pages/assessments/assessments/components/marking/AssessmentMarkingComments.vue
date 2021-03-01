<template>
    <div
        class="w-full my-4"
    >
        <label 
            class="block text-gray-700 font-bold mb-2" 
            for="comment"
        >
            Comment
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
                this.updateMarkingComment({
                    id: this.mark ? this.mark.id : null,
                    questionId: this.question.id,
                    itemId: this.question.question_item,
                    comment: this.comment
                })
            }, 500)
        }
    },

    methods: {
        ...mapActions({
            updateMarkingComment: 'assessments/updateMarkingComment'
        })
    },

    mounted () {
        this.mark = find(this.participantAnswer.marks, mark => mark.question_id === this.question.id)

        if (this.mark) {
            this.comment = this.mark.description
        }

        window.events.$on('assessment:mark-update', mark => {
            if (mark.question_id === this.question.id) {
                this.mark = mark
                this.comment = mark.description
            }
        })
    }
}
</script>