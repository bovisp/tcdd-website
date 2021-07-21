<template>
    <div>
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <strong class="mr-2">{{ trans('generic.totalscore') }}:</strong> {{ totalScore }}
                </div>
            </div>

            <div class="level-right">
                <div class="level-item">
                    <b-button 
                        type="is-info"
                        :class="{ 'btn-disabled': assessment.locked }"
                        @click.prevent="add"
                        :disabled="assessment.locked"
                    >
                        <b-icon
                            icon="plus"
                            size="is-small"
                            class="mr-1"
                        />

                        {{ trans('generic.addpage') }}
                    </b-button>
                </div>

                <div 
                    class="level-item"
                    v-if="pages.length !== 0"
                >
                    <b-field>
                        <b-select 
                            v-model="page"
                            @change.native="setPage"
                        >
                            <option
                                :value="p.number"
                                v-for="p in pages"
                                :key="p.id"
                                v-text="`${trans('generic.page')} ${p.number}`"
                            ></option>
                        </b-select>
                    </b-field>
                </div>
            </div>
        </div>

        <template v-if="currentPage">
            <assessment-page />
        </template>
    </div>
    <!-- <div id="list-container" class="content">
        <ol id="list">
          
        </ol>
    </div> -->
</template>

<script>
import autoScroll from 'dom-autoscroller'
import dragula from 'dragula'
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            page: null
        }
    },

    computed: {
        ...mapGetters({
            pages: 'assessments/pages',
            totalScore: 'assessments/totalScore',
            assessment: 'assessments/assessment',
            currentPage: 'assessments/currentPage'
        })
    },

    methods: {
        ...mapActions({
            add: 'assessments/addPage',
            fetch: 'assessments/fetchPages',
            setCurrentPage: 'assessments/setCurrentPage' 
        }),

        async setPage () {
            await this.setCurrentPage(this.page)

            this.changePageNumber()
        },

        changePageNumber () {
            if (!this.currentPage) {
                this.page = null

                return
            }
            
            this.page = this.currentPage.number
        }
    },

    async mounted () {
        await this.fetch(this.assessment.id)

        this.changePageNumber()
        // var drake = dragula([document.querySelector('#list')]);

        // drake.on('dragend', () => console.log('dropped'))

        // var scroll = autoScroll([
        //         window,
        //         document.querySelector('#list-container')
        //     ],{
        //     margin: 20,
        //     autoScroll: function(){
        //         return this.down && drake.dragging;
        //     }
        // });
    }
}
</script>