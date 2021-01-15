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
            <assessment-questions-add />
        </template>

        <template v-if="type === 'content'">
            <assessment-questions-content-builder-add />
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
import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        type: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            data: null
        }
    },

    computed: {
        ...mapGetters({
            currentPage: 'assessments/currentPage'
        })
    },

    methods: {
        ...mapActions({
            fetchPages: 'assessments/fetchPages'
        }),

        async destroy () {
            if (this.type === 'content') {
                let { data } = await axios.delete(`${this.urlBase}/api/assessments/page/${this.currentPage.id}/content`, {
                    data: {
                        type: this.type,
                        data: this.data
                    }
                })
            }

            this.$emit('content-add:cancel')

            window.events.$emit('content:adding', false)
        },

        async add () {  
            await this.fetchPages(this.currentPage.assessment_id)

            window.events.$emit('assessment-page:content-added')
        }
    },

    mounted () {
        window.events.$on('assessment-page:content-builder-data', data => {
            this.data = data
        })
    }
}
</script>