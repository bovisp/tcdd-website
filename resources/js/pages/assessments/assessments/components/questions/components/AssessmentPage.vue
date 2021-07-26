<template>
    <div v-if="page">
        <div class="flex items-center">
            <h2 class="subtitle is-4 mb-0">
                {{ trans('generic.page') }} {{ page.number }}
            </h2>

            <b-button 
                type="is-text"
                class="has-text-danger"
                size="is-medium"
                icon-right="delete"
                :title="trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepage')"
                @click.prevent="$buefy.dialog.confirm({
                    title: `${trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepage')}`,
                    message: `${trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepageconfirm')}`,
                    confirmText: `${trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepage')}`,
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => destroy()
                })" 
            />

            <b-button 
                type="is-text"
                v-if="assessment.pages > 1"
                size="is-medium"
                icon-right="pencil"
                :title="trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.updatepagenumber')"
                @click.prevent="updatePage = true" 
            />
        </div>

        <assessment-page-update 
            v-if="updatePage && assessment.pages > 1"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            updatePage: false
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
            destroyPage: 'assessments/destroyPage'
        }),

        destroy () {
            this.destroyPage()

            window.events.$emit('page:deleted')

            this.$buefy.toast.open({
                message: `${this.trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.pagedeleted')}`,
                type: 'is-success'
            })
        }
    },

    mounted () {
        window.events.$on('page:update', pageNumber => this.updatePage = false)
    }
}
</script>