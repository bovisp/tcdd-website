<template>
    <div class="mt-6">
        <div class="flex mb-6">
            <button 
                class="btn btn-blue text-sm btn-sm"
                @click.prevent="add"
                v-if="(type === 'content') || (type === 'question' && questionAdded)"
            >
                Add to page
            </button>

            <button 
                class="btn btn-text text-sm btn-sm ml-auto"
                @click.prevent="destroy"
            >
                Cancel
            </button>
        </div>

        <template v-if="type === 'question'">
            <assessment-questions-add 
                :page="page"
                @content-builder:add="addQuestion"
                v-if="(type === 'content') || (type === 'question' && questionAdded)"
            />
        </template>

        <template v-if="type === 'content'">
            <assessment-questions-content-builder-add 
                :page="page"
                @content-builder:add="setData"
            />
        </template>

        <div class="flex mt-6">
            <button 
                class="btn btn-blue text-sm btn-sm"
                @click.prevent="add"
                v-if="(type === 'content') || (type === 'question' && questionAdded)"
            >
                Add to page
            </button>

            <button 
                class="btn btn-text text-sm btn-sm ml-auto"
                @click.prevent="destroy"
            >
                Cancel
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: true
        },
        page: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            data: {},
            questionAdded: false
        }
    },

    methods: {
        setData (data) {
            this.data = data
        },

        async destroy () {
            if (this.type === 'content') {
                let { data } = await axios.delete(`${this.urlBase}/api/assessments/page/${this.page.id}/content`, {
                    data: {
                        type: this.type,
                        data: this.data
                    }
                })
            }

            this.$emit('content-add:cancel')

            window.events.$emit('content:adding', false)
        },

        add () {
            if (this.type === 'content') {
                window.events.$emit('content-add:push', {
                    order: this.data.data.assessmentPageContent.order,
                    type: this.type,
                    data: this.data.data
                })
            }

            this.$emit('content-add:cancel')

            window.events.$emit('content:adding', false)
        },

        async addQuestion (payload) {
            this.questionAdded = true

            let { data } = await axios.post(`${this.urlBase}/api/assessments/page/${this.page.id}/content`, {
                type: 'question',
                question_score: payload.score,
                question_id: payload.question.id
            })

            window.events.$emit('content-add:push', {
                order: data.assessmentPageContent.order,
                type: this.type,
                data: {
                    model: data.assessmentPageContentItem,
                    content: payload.question
                }
            })
        }
    }
}
</script>