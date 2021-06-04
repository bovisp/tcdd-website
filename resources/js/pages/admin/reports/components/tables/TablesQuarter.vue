<template>
    <div>
        <tabs>
            <tab  
                :name="trans('js_pages_admin_reports_components_tables_tablesquarter.views')" 
                :selected="true"
            >
                <tables-quarter-viewed
                    :quarters="quarters"
                />
            </tab>

            <!-- <tab  
                :name="trans('js_pages_admin_reports_components_tables_tablesquarter.completions')" 
            >
                <tables-quarter-completed />
            </tab> -->
        </tabs>        
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