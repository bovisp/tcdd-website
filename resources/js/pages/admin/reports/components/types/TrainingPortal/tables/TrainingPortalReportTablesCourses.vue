<template>
    <tabs>
        <tab  
            name="Course views" 
            :selected="true"
        >
            <table class="border-collapse border-0 w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">
                            {{ trans('generic.course') }}
                        </th>
                        <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">
                            {{ trans('js_pages_admin_reports_components_types_trainingportal_tables_trainingportalreporttablescourses.views') }}
                        </th>
                    </tr>
                </thead>
            
                <tbody v-if="typeof stats.totals !== 'undefined'">
                    <tr
                        v-for="course in courses"
                        :key="course.courseId"
                    >
                        <td class="px-4 py-2">{{ course.courseName }}</td>
                        <td class="px-4 py-2 text-right">{{ course.views }}</td>
                    </tr>

                    <tr>
                        <td class="px-4 py-2"><strong>{{ trans('js_pages_admin_reports_components_types_trainingportal_tables_trainingportalreporttablescourses.totals') }}:</strong></td>
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

    computed: {
        courses () {
            if (typeof this.stats.totals !== 'undefined') {
                return orderBy(this.stats.totals.courses, ['views'], ['desc'])
            }
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