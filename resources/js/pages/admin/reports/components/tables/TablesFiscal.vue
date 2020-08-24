<template>
    <div>
        <tabs>
            <tab  
                name="Views by fiscal year" 
                :selected="true"
            >
                <tables-fiscal-viewed
                    :fiscal-years="fiscalYears"
                    :fiscal-years-data="fiscalYearsData"
                />
            </tab>

            <!-- <tab  
                name="Completions by fiscal year" 
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