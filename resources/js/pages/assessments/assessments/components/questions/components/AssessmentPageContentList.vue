<template>
    <div class="flex my-4">
        <div 
            class="w-2/12 flex items-start"
        >
            <i class="fas fa-arrows-alt ml-auto cursor-move"></i>

            <i 
                v-if="data.type === 'Question'"
                class="fas fa-edit ml-2"
                title="Edit question"
                @click.prevent="editQuestion"
            ></i>

            <i 
                class="fas fa-trash-alt text-red-500 ml-2"
                @click.prevent="destroy"
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
    </div>
</template>

<script>
import { orderBy } from 'lodash-es'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    methods: {
        orderBy,

        editQuestion () {
            let questionId = this.data.items[0].question.id

            window.location.href = `${this.urlBase}/questions?question=${questionId}`
        },

        async destroy () {
            let type = this.data.type === 'ContentBuilder' ? 'content' : 'question'

            let { data } = await axios.delete(`${this.urlBase}/api/assessments/page/${this.data.model.assessment_page_id}/content/${this.data.model.id}`)

            window.events.$emit('assessment-pages:reload')
        }
    }
}
</script>