<template>
    <div class="flex my-4">
        <div 
            class="w-2/12 flex items-start"
        >
            <i 
                class="fas fa-arrows-alt ml-auto cursor-move"
                v-if="!lockStatus || (lockStatus && data.type === 'ContentBuilder')"
            ></i>

            <i 
                v-if="data.type === 'Question'"
                class="fas fa-edit ml-2"
                :class="{ 'ml-auto':  lockStatus }"
                title="Edit question"
                @click.prevent="editQuestion"
            ></i>

            <i 
                class="fas fa-trash-alt text-red-500 ml-2"
                @click.prevent="confirmDestroy"
                :title="`Delete ${data.type === 'ContentBuilder' ? 'content' : 'question'}`"
                v-if="!lockStatus || (lockStatus && data.type === 'ContentBuilder')"
            ></i>
        </div>

        <div
            class="w-10/12 pl-4"
        >
            <template v-if="data.type === 'ContentBuilder'">
                <div class="mb-6">
                    <assessment-page-content-builder 
                        v-for="item in orderBy(data.items, ['id'], ['asc'])"
                        :key="item.id"
                        :content-builder-id="item.id"
                        :lang="item.lang"
                    />
                </div>
            </template>

            <template v-if="data.type === 'Question'">
                <div class="mb-6">
                    <assessment-page-question 
                        :question="data"
                    />
                </div>
            </template>
        </div>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
        >
            <template slot="header">
                Delete assessment {{ data.type === 'ContentBuilder' ? 'content' : 'question' }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        Are you sure you want to delete this {{ data.type === 'ContentBuilder' ? 'content' : 'question' }}?
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { orderBy } from 'lodash-es'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment',
            lockStatus: 'assessments/lockStatus'
        })
    },

    data () {
        return {
            modalActive: false
        }
    },

    methods: {
        ...mapActions({
            deleteAssessmentPageItem: 'assessments/deleteAssessmentPageItem'
        }),

        orderBy,

        editQuestion () {
            let questionId = this.data.items[0].question.id

            window.location.href = `${this.urlBase}/questions?question=${questionId}`
        },

        confirmDestroy () {
            this.modalActive = true
        },

        close () {
            this.modalActive = false
        },

        async destroy () {
            this.close()
            
            await this.deleteAssessmentPageItem(this.data.model.id)
        }
    }
}
</script>