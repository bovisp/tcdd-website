<template>
    <div>
        <tabs>
            <tab  
                :name="trans('js_pages_admin_reports_components_tables_tablesfiscal.views')" 
                :selected="true"
            >
                <tables-fiscal-viewed
                    :fiscal-years="fiscalYears"
                    :fiscal-years-data="fiscalYearsData"
                />
            </tab>

            <!-- <tab  
                :name="trans('js_pages_admin_reports_components_tables_tablesfiscal.completions')" 
            >
                <tables-fiscal-completed />
            </tab> -->
        </tabs>        
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