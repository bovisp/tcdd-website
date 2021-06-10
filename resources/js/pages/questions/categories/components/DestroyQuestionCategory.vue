<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            {{ trans('js_pages_questions_categories_components_destroyquestioncategory.deletecategory') }}
        </button>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
            ok-button-text="Submit"
            cancel-button-text="Cancel"
        >
            <template slot="header">
                {{ trans('js_pages_questions_categories_components_destroyquestioncategory.deletecategory') }}: {{ questionCategory.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        {{ trans('js_pages_questions_categories_components_destroyquestioncategory.deletecategoryconfirm1') }}
                        <strong>{{ trans('js_pages_questions_categories_components_destroyquestioncategory.deletecategoryconfirm2') }}</strong>.
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