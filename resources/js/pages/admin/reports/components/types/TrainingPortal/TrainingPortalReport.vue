<template>
    <div class="w-10/12 p-4">
        <div class="mb-4">
            <button
                class="btn mr-4"
                :class="btnGraphsState"
                @click.prevent="viewGraphs = true"
            >{{ trans('js_pages_admin_reports_components_types_trainingportal_trainingportalreport.viewgraphs') }}</button>
            
            <button
                class="btn"
                :class="btnViewsState"
                @click.prevent="viewGraphs = false"
            >{{ trans('js_pages_admin_reports_components_types_trainingportal_trainingportalreport.viewtables') }}</button>
        </div>

        <template v-if="viewGraphs && isEmpty(stats) === false">
            <training-portal-report-graphs 
                :data="stats"
            />
        </template>

        <template v-if="!viewGraphs && isEmpty(stats) === false">
            <training-portal-report-tables 
                :data="stats"
            />
        </template>
    </div>
</template>

<script>
import { isEmpty } from 'lodash-es'

export default {
    data () {
        return {
            stats: {},
            viewGraphs: true
        }
    },

    computed: {
        btnGraphsState () {
            return this.viewGraphs ? 'btn-blue' : 'btn-outline btn-outline-blue'
        },

        btnViewsState () {
            return this.viewGraphs ? 'btn-outline btn-outline-blue' : 'btn-blue'
        }
    },

    methods: {
        isEmpty
    },

    mounted () {
        window.events.$on('report:data', stats => {
            this.stats = stats
        })
    }
}
</script>