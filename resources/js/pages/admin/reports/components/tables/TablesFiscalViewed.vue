<template>
    <div>
        <table class="border-collapse border-0 w-full text-sm">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">
                        {{ trans('js_pages_admin_reports_components_tables_tablesfiscalviewed.fiscalyear') }}
                    </th>
                    <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">
                        {{ trans('js_pages_admin_reports_components_tables_tablesfiscalviewed.modulesviewed') }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="year in fiscalYears"
                    :key="year"
                >
                    <td class="px-4 py-2">FY{{ year }}-{{ String(year+1).match(/\d{2}$/)[0] }}</td>
                    <td class="px-4 py-2 text-right">{{ fiscalYearsData[year] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        fiscalYears: {
            type: Array,
            required: true
        },
        fiscalYearsData: {
            type: Object,
            required: true
        }
    }
}
</script>