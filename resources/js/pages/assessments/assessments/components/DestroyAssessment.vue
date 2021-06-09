<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            {{ trans('js_pages_assessments_assessments_components_destroyassessment.delete') }} {{ isDuplicate ? trans('js_pages_assessments_assessments_components_destroyassessment.duplicate'): '' }}{{ trans('js_pages_assessments_assessments_components_destroyassessment.assessment') }}
        </button>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
            ok-button-text="Submit"
            cancel-button-text="Cancel"
        >
            <template slot="header">
                {{ trans('js_pages_assessments_assessments_components_destroyassessment.delete') }} {{ isDuplicate ? trans('js_pages_assessments_assessments_components_destroyassessment.duplicate'): '' }}{{ trans('js_pages_assessments_assessments_components_destroyassessment.assessment') }}: {{ assessment.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        {{ trans('js_pages_assessments_assessments_components_destroyassessment.deleteassessmentconfirm1') }}
                        <strong>{{ trans('js_pages_assessments_assessments_components_destroyassessment.deleteassessmentconfirm2') }}</strong>.
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
            assessment: 'assessments/assessment',
            isDuplicate: 'assessments/isDuplicate'
        })
    },

    methods: {
        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/assessments/${this.assessment.id}`)

            this.close()

            this.$emit('close')
        }
    },
}
</script>