<template>
    <div>
        <tabs>
            <tab  
                name="Views by quarter" 
                :selected="true"
            >
                <tables-quarter-viewed
                    :quarters="quarters"
                />
            </tab>

            <!-- <tab  
                name="Completions by quarter" 
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