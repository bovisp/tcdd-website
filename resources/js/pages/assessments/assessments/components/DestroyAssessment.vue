<template>
    <div>
        <b-button 
            type="is-text has-text-danger"
            @click.prevent="isComponentModalActive = true"
        >
            <span v-if="!isDuplicating">{{ trans('generic.delete') }} {{ trans('generic.assessment') }}</span>
            
            <span v-else>{{ trans('generic.cancelduplication') }}</span>
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
import { mapGetters, mapMutations } from 'vuex'

export default {
    data () {
        return {
            isComponentModalActive: false
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment',
            isDuplicating: 'assessments/isDuplicating'
        })
    },

    methods: {
        ...mapMutations({
            setDuplicationStatus: 'assessments/SET_DUPLICATION_STATUS'
        }),

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/assessments/${this.assessment.id}`)

            await this.setDuplicationStatus(false)

            this.isComponentModalActive = false

            this.$buefy.toast.open({
                message: data.data.message,
                type: 'is-success'
            })

            this.$emit('close')
        }
    }
}
</script>