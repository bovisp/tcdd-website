<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            {{ trans('js_pages_questions_questions_components_destroyquestion.deletequestion') }}
        </button>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
        >
            <template slot="header">
                Delete question: {{ question.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        {{ trans('js_pages_questions_questions_components_destroyquestion.deletequestionconfirm1') }}
                        <strong>{{ trans('js_pages_questions_questions_components_destroyquestion.deletequestionconfirm2') }}</strong>.
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
            question: 'questions/question'
        })
    },

    methods: {
        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/questions/${this.question.id}`)

            this.close()

            this.$emit('close')
        }
    },
}
</script>