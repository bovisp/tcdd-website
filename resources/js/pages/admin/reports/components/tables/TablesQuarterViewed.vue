<template>
    <div>
        <table class="border-collapse border-0 w-full text-sm">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">Fiscal year</th>
                    <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">Quarter</th>
                    <th class="px-4 py-2 border-2 border-t-0 border-l-0 border-r-0 text-gray-800">Modules viewed</th>
                </tr>
            </thead>
        
            <tbody>
                <tr
                    v-for="quarter in quarters"
                    :key="quarter.id"
                >
                    <td class="px-4 py-2">FY{{ quarter.year }}-{{ String(quarter.year + 1).match(/\d{2}$/)[0] }}</td>
                    <td class="px-4 py-2">Q{{ quarter.quarter }}</td>
                    <td class="px-4 py-2 text-right">{{ quarter.totals }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        quarters: {
            type: Array,
            required: true
        }
    }
}
</script>