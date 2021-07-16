<template>
    <div>
        <b-button 
            type="is-text has-text-danger"
            @click.prevent="isComponentModalActive = true"
        >
            {{ trans('generic.delete') }} {{ trans('generic.assessment') }}
        </b-button>

        <b-modal
            v-model="isComponentModalActive"
            has-modal-card
            trap-focus
            :destroy-on-hide="false"
            aria-role="dialog"
            :aria-label="`${trans('generic.delete')} ${trans('generic.assessment')}`"
            aria-modal
        >
            <template #default="props">
                <div class="modal-card" style="width: auto">
                    <header class="modal-card-head">
                        <p class="modal-card-title">
                            {{ trans('generic.delete') }} {{ trans('generic.assessment') }}: {{ assessment.name }}
                        </p>

                        <button
                            type="button"
                            class="delete"
                            @click="isComponentModalActive = false"
                        ></button>
                    </header>

                    <section class="modal-card-body">
                        {{ trans('js_pages_assessments_assessments_components_destroyassessment.deleteassessmentconfirm1') }}
                        <strong class="has-text-danger">{{ trans('js_pages_assessments_assessments_components_destroyassessment.deleteassessmentconfirm2') }}</strong>.
                    </section>

                    <footer class="modal-card-foot">
                        <b-button
                            :label="trans('generic.cancel')"
                            @click.prevent="isComponentModalActive = false"
                        />

                        <b-button
                            :label="trans('generic.delete')"
                            type="is-danger"
                            @click.prevent="destroy"
                        />
                    </footer>
                </div>
            </template>
        </b-modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data () {
        return {
            isComponentModalActive: false
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/assessments/${this.assessment.id}`)

            this.isComponentModalActive = false

            this.$toasted.success(data.message)

            this.$emit('close')
        }
    }
}
</script>