<template>
    <div v-if="page" id="page-top">
        <div class="flex items-center mb-4">
            <h2 class="subtitle is-4 mb-0">
                {{ trans('generic.page') }} {{ page.number }}
            </h2>

            <b-button 
                type="is-text"
                class="has-text-danger"
                size="is-medium"
                icon-right="delete"
                :title="trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.deletepage')"
                :disabled="assessment.locked === true"
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
                :disabled="assessment.locked === true"
                @click.prevent="updatePage = true" 
            />

            <assessment-item-add />

            <assessment-page-update 
                v-if="updatePage && assessment.pages > 1"
            />
        </div>

        <draggable
            :list="page.data"
            @start="drag = true"
            @end="drag = false"
            @change="updateOrder"
            handle=".drag-item"
        >
            <assessment-page-item 
                v-for="item in orderBy(page.data, ['order'], ['asc'])"
                :key="item.order"
                :data="item"
            />
        </draggable>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { Draggable } from 'vuedraggable'
import { orderBy } from 'lodash-es'

export default {
    componets: {
        Draggable
    },

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
            destroyPage: 'assessments/destroyPage',
            changeCurrentPageItemOrder: 'assessments/changeCurrentPageItemOrder'
        }),

        orderBy,

        destroy () {
            this.destroyPage()

            window.events.$emit('page:deleted')

            this.$buefy.toast.open({
                message: `${this.trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.pagedeleted')}`,
                type: 'is-success'
            })
        },

        async updateOrder (e) {
            await this.changeCurrentPageItemOrder({
                moved: e.moved.element.model.id,
                newOrderNumber: e.moved.newIndex + 1,
                oldOrderNumber: e.moved.oldIndex + 1
            })

            window.events.$emit('page:item-reorder')

            this.$scrollTo('#page-top')
        }
    },

    mounted () {
        window.events.$on('page:update', pageNumber => this.updatePage = false)
    }
}
</script>