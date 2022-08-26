<template>
    <div class="flex ml-auto">
        <b-button 
            type="is-text"
            class="has-text-info"
            icon-left="plus"
            v-if="!adding"
            @click.prevent="adding = true"
        >
            {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionscontentpicker.addcontent') }}
        </b-button>

        <div
            v-else
            class="flex"
        >
            <b-select 
                v-model="contentType"
            >
                <option
                    :value="type.code"
                    v-for="type in types"
                    :key="type.code"
                    v-text="type.name"
                ></option>
            </b-select>

            <b-button
                type="is-text"
                class="has-text-info"
                @click.prevent="adding = false"
            >
                {{ trans('generic.cancel') }}    
            </b-button>
        </div>

        <assessment-item-add-modal 
            v-if="contentType"
            :type="contentType"
            @cancel="cancel"
        />
    </div>
</template>

<script>
import { mapMutations, mapGetters } from 'vuex'

export default {
    data () {
        return {
            adding: false,
            contentType: null
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        }),

        types () {
            if (this.assessment.locked) {
                return [
                    { code: 'content', name: this.trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionscontentpicker.explanitorycontent') }
                ]
            }

            return [
                { code: 'question', name: this.trans('generic.question') },
                { code: 'content', name: this.trans('js_pages_assessments_assessments_components_questions_components_assessmentquestionscontentpicker.explanitorycontent') }
            ]
        }
    },

    methods: {
        ...mapMutations({
            clearAvailableQuestions: 'assessments/SET_AVAILABLE_QUESTIONS'
        }),

        cancel () {
            this.contentType = ''

            this.adding = false

            this.clearAvailableQuestions([])
        }
    },

    mounted () {
        window.events.$on('assessment:question-added', () => {
            this.contentType = null
            this.adding = false
        })

        window.events.$on('assessment:content-added', () => {
            this.contentType = null
            this.adding = false
        })
    }
}
</script>