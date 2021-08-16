<template>
    <div class="level mt-2">
        <div class="level-left">
            <div class="level-item">
                <b-field>
                    <b-select 
                        v-model="pageNumber"
                        size="is-small"
                        :placeholder="`${trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.changepagenumber')}...`"
                    >
                        <option
                            :value="p"
                            v-for="p in pages"
                            :key="p"
                            v-text="`${trans('generic.page')} ${p}`"
                        ></option>
                    </b-select>
                </b-field>
            </div>

            <div class="level-item">
                <b-button 
                    type="is-info"
                    size="is-small"
                    @click.prevent="update"
                    :disabled="!pageNumber"
                >
                    {{ trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.change') }}
                </b-button>
            </div>
        </div>

        <div class="level-right"></div>        
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { filter } from 'lodash-es'

export default {
    data () {
        return {
            pageNumber: null,
            pages: []
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment',
            page: 'assessments/page'
        })
    },

    methods: {
        ...mapActions({
            updatePageNumber: 'assessments/updatePageNumber'
        }),

        filter,

        async update () {
            await this.updatePageNumber({
                newPageNumber: this.pageNumber,
                oldPageNumber: this.page.number
            })

            this.$buefy.toast.open({
                message: `${this.trans('js_pages_assessments_assessments_components_questions_components_assessmentpage.changepagenumbersuccess')}`,
                type: 'is-success'
            })

            window.events.$emit('page:update', this.pageNumber)
        },
    },

    mounted () {
        this.pages = Array.from({length: this.assessment.pages}, (_, i) => i + 1)
            .filter(p => p !== this.page.number)
    }
}
</script>