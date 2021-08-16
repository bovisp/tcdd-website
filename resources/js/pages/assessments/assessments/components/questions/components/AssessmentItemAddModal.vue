<template>
    <b-modal
        v-model="isActive"
        has-modal-card
        trap-focus
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
                    @click="cancel"/>
            </header>
            
            <section class="modal-card-body">
                <assessment-item-add-question 
                    v-if="type === 'question'"
                    @assessment:question-add="addQuestion"
                />

                <assessment-questions-content-builder-add 
                    v-else
                />
            </section>

            <footer 
                class="modal-card-foot"
            >
                <b-button
                    :label="trans('generic.cancel')"
                    @click="cancel"
                />

                <b-button
                    :label="trans('generic.add')"
                    type="is-info"
                    v-if="type === 'content'"
                    @click.prevent="addContent"
                />
            </footer>
        </div>
    </b-modal>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
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
            isActive: false,
            data: null
        }
    },

    computed: {
        ...mapGetters({
            page: 'assessments/page',
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        ...mapActions({
            addQuestionToPage: 'assessments/addQuestionToPage'
        }),

        sentenceCase,

        cancel () {
            if (this.type === 'content') {
                this.destroy()

                return
            }

            this.$emit('cancel')
        },

        async addQuestion(question) {
            await this.addQuestionToPage(question)

            window.events.$emit('assessment:question-added')

            this.isActive = false
        },

        addContent () {
            window.events.$emit('assessment:content-added')

            this.isActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/assessments/${this.assessment.id}/page/${this.page.id}/content`, {
                data: {
                    type: this.type,
                    data: this.data
                }
            })

            this.$emit('cancel')
        },
    },

    mounted () {
        this.isActive = this.type ? true : false

        window.events.$on('assessment-page:content-builder-data', data => {
            this.data = data
        })
    }
}
</script>