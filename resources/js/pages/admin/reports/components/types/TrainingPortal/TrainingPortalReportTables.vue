<template>
    <div class="flex w-full overflow-y-auto" style="height: 500px;">
        <div class="w-6/12 p-2">
            <training-portal-report-tables-fiscal
                :data="data"
            />
        </div>

        <div class="w-6/12 p-2">
            <training-portal-report-tables-courses
                :data="data"
            />
        </div>
    </div>
</template>

<script>
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
    }
}
</script>