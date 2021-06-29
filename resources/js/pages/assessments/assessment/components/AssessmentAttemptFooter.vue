<template>
    <div
        class="fixed h-16 w-full bottom-0 left-0 mx-auto flex items-center px-8 bg-gray-100"
        style="box-shadow: 0px 0 10px rgba(0, 0, 0, 0.1);"
        v-if="attempt"
    >
        <assessment-attempt-timer />

        <template 
            v-if="attempt.pages && !reviewStatus"
        >
            <button 
                class="btn btn-blue ml-auto"
                :class="hasPrevPage ? '' : 'btn-disabled'"
                :disabled="!hasPrevPage"
                @click.prevent="changePage(currentPage.number - 1)"
            >
                <i class="fas fa-chevron-left"></i>

               {{ trans('js_pages_assessments_assessment_components_assessmentattemptfooter.previouspage') }}
            </button>

            <button 
                class="btn btn-blue ml-4"
                :class="hasNextPage ? '' : 'btn-disabled'"
                :disabled="!hasNextPage"
                @click.prevent="changePage(currentPage.number + 1)"
            >
                {{ trans('js_pages_assessments_assessment_components_assessmentattemptfooter.nextpage') }}

                <i class="fas fa-chevron-right"></i>
            </button>

            <button 
                class="btn btn-green ml-4"
                @click.prevent="review"
            >
                {{ trans('js_pages_assessments_assessment_components_assessmentattemptfooter.reviewallsubmit') }}
            </button>
        </template>

        <template v-else>
            <button 
                class="btn btn-green ml-auto"
                @click.prevent="review"
            >
                {{ trans('js_pages_assessments_assessment_components_assessmentattemptfooter.returnassessment') }}
            </button>

            <button 
                class="btn btn-blue ml-4"
                @click.prevent="modalActive = true"
            >
                {{ trans('js_pages_assessments_assessment_components_assessmentattemptfooter.submitfinish') }}
            </button>

            <modal 
                v-show="modalActive"
                @close="close"
                @submit="submit"
                ok-button-text="Submit"
                cancel-button-text="Cancel"
                :has-spinner="true"
            >
                <template slot="header" v-if="attempt.assessment">
                    {{ trans('js_pages_assessments_assessment_components_assessmentattemptfooter.submit') }} {{ attempt.assessment.name }}
                </template>

                <template slot="body">
                    <div class="my-4">
                        <p 
                            class="text-red-700 mb-4"
                            v-if="hasIncompleteQuestions"
                        >
                            <strong>{{ trans('js_pages_assessments_assessment_components_assessmentattemptfooter.incompletequestions') }}</strong>
                        </p>

                        {{ trans('js_pages_assessments_assessment_components_assessmentattemptfooter.incompletequestionsconfirm') }}
                    </div>
                </template>
            </modal>
        </template>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            hasConfirmed: false,
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            attempt: 'assessment/attempt',
            currentPage: 'assessment/currentPage',
            reviewStatus: 'assessment/reviewStatus',
            hasIncompleteQuestions: 'assessment/hasIncompleteQuestions'
        }),

        hasPrevPage () {
            if (this.attempt.pages.length === 1 || this.currentPage.number === 1) {
                return false
            }

            return true
        },

        hasNextPage () {
            if (this.attempt.pages.length === 1 || this.currentPage.number === this.attempt.pages.length) {
                return false
            }

            return true
        }
    },

    methods: {
        ...mapActions({
            changePage: 'assessment/changePage',
            setReviewStatus: 'assessment/setReviewStatus',
            submitAssessment: 'assessment/submitAssessment'
        }),

        review () {
            this.setReviewStatus(!this.reviewStatus)
        },
        
        close () {
            this.modalActive = false
        },

        async submit () {
            await this.submitAssessment()
        }
    }
}
</script>