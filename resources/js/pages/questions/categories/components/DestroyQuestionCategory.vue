<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            Delete question bank category
        </button>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
        >
            <template slot="header">
                Delete question bank category: {{ questionCategory.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        Are you sure you want to do this? All questions associated with this category will be 
                        added to the Miscellaneous category.
                        <strong>Only do this if you are absolutely sure this is what you want</strong>.
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            questionCategory: 'questionCategories/questionCategory'
        })
    },

    methods: {
        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/questions/categories/${this.questionCategory.id}`)

            this.close()

            this.$emit('close')
        }
    },
}
</script>