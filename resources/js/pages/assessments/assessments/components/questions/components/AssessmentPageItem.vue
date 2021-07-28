<template>
    <div class="flex w-full">
        <div class="w-2/12 flex items-start">
            <b-icon
                icon="arrow-all"
                class="mt-3"
                style="cursor: move;"
            ></b-icon>

            <b-button
                icon-right="delete"
                type="is-text"
                size="is-medium"
                :title="`${trans('generic.delete')} ${data.type === 'ContentBuilder' ? trans('generic.content') : trans('generic.question')}`"
                class="has-text-danger"
                @click.prevent="$buefy.dialog.confirm({
                    title: `${trans('generic.delete')} ${noCase(trans('generic.question'))}`,
                    message: `${trans('js_pages_assessments_assessments_components_questions_components_assessmentpagecontentlist.deleteassessmentconfirm')} ${data.type === 'ContentBuilder' ? trans('generic.content') : trans('generic.question')}?`,
                    confirmText: `${trans('generic.delete')} ${noCase(trans('generic.question'))}`,
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => destroy()
                })" 
            ></b-button>
        </div>

        <div class="w-10/12">
            <!-- <template v-if="data.type === 'ContentBuilder'">
                <div class="mb-6">
                    <assessment-page-content-builder 
                        v-for="item in orderBy(data.items, ['id'], ['asc'])"
                        :key="item.id"
                        :content-builder-id="item.id"
                        :lang="item.lang"
                    />
                </div>
            </template> -->

            <template v-if="data.type === 'Question'">
                <div class="mb-6">
                    <assessment-page-question 
                        :question="data"
                    />
                </div>
            </template>
        </div>
        
    </div>
</template>

<script>
import { noCase } from 'change-case'
import { mapActions } from 'vuex'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    methods: {
        ...mapActions({
            deleteAssessmentPageItem: 'assessments/deleteAssessmentPageItem'
        }),

        noCase,

        async destroy () {
            await this.deleteAssessmentPageItem(this.data.model.id)

            window.events.$emit('page:item-delete')

            this.$buefy.toast.open({
                message: 'Question successfully deleted.',
                type: 'is-success'
            })
        }
    }
}
</script>