<template>
    <tabs>
        <tab  
            name="Views by fiscal year" 
            :selected="true"
        >
            <table class="border-collapse border-0 w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">Quarter</th>
                        <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">Modules viewed</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="quarter in orderBy(stats.quarters, ['year', 'quarter'], ['asc', 'asc'])"
                        :key="quarter.timestampStart"
                    >
                        <td class="px-4 py-2">FY{{ quarter.year }}-{{ String(quarter.year + 1).match(/\d{2}$/)[0] }} Q{{ quarter.quarter }}</td>
                        <td class="px-4 py-2 text-right">{{ quarter.views }}</td>
                    </tr>

                    <tr v-if="typeof stats.totals !== 'undefined'">
                        <td class="px-4 py-2"><strong>Totals:</strong></td>
                        <td class="px-4 py-2 text-right">{{ stats.totals.totals }}</td>
                    </tr>
                </tbody>
            </table>
        </tab>
    </tabs>
</template>

<script>
import { orderBy } from 'lodash-es'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            stats: {}
        }
    },

    methods: {
        orderBy
    },

    mounted () {
        this.stats = this.data

        window.events.$on('report:data', stats => {
            this.stats = stats
        })
    }
}
</script>