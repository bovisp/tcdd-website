<template>
    <div class="mt-6">
        <div class="flex mb-6">
            <button 
                class="btn btn-blue text-sm btn-sm"
                @click.prevent="add"
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
            <p>question</p>
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
            data: {}
        }
    },

    methods: {
        setData (data) {
            this.data = data
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/assessments/page/${this.page.id}/content`, {
                data: {
                    type: this.type,
                    data: this.data
                }
            })

            this.$emit('content-add:cancel')
        },

        add () {
            window.events.$emit('content-add:push', {
                order: this.data.data.assessmentPageContent.order,
                data: this.data.data
            })

            this.$emit('content-add:cancel')
        }
    }
}
</script>