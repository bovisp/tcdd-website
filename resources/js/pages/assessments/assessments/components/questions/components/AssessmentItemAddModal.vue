<template>
    <b-modal
        v-model="isActive"
        has-modal-card
        trap-focus
        :destroy-on-hide="false"
        aria-role="dialog"
        aria-label="Example Modal"
        aria-modal
    >
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ trans('generic.add') }}: {{ sentenceCase(type) }}
                </p>

                <button
                    type="button"
                    class="delete"
                    @click="$emit('cancel')"/>
            </header>
            
            <section class="modal-card-body">
                <assessment-item-add-question 
                    v-if="type === 'question'"
                    @assessment:question-add="addQuestion"
                />
            </section>

            <footer 
                class="modal-card-foot"
            >
                <b-button
                    :label="trans('generic.cancel')"
                    @click="$emit('cancel')"
                />

                <b-button
                    :label="trans('generic.add')"
                    type="is-info"
                    v-if="type === 'content'"
                />
            </footer>
        </div>
    </b-modal>
</template>

<script>
import { mapActions } from 'vuex'
import { sentenceCase } from 'change-case'

export default {
    props: {
        type: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            isActive: false
        }
    },

    methods: {
        ...mapActions({
            addQuestionToPage: 'assessments/addQuestionToPage'
        }),

        sentenceCase,

        async addQuestion(question) {
            await this.addQuestionToPage(question)

            window.events.$emit('assessment:question-added')

            this.isActive = false
        }
    },

    mounted () {
        this.isActive = this.type ? true : false
    }
}
</script>