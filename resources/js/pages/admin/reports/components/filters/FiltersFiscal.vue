<template>
    <div>
        <strong class="block font-bold text-md mb-2">
            Fiscal year
        </strong>

        <label 
            class="block mb-1"
            id="fiscal"
            v-for="year in years"
            :key="year"
        >
            <input 
                type="checkbox" 
                class="form-checkbox h-5 w-5 text-gray-600"
                name="fiscal"
                :value="year"
                v-model="fiscal"
            >
                <span class="ml-2 text-gray-700">FY{{ year }}-{{ (year + 1).toString().slice(-2) }}</span>
        </label>
    </div>
</template>

<script>
export default {
    data () {
        return {
            fiscal: [],
            years: []
        }
    },

    watch: {
        fiscal () {
            window.events.$emit('report:filter-fiscal', this.fiscal)
        }
    },

    mounted () {
        window.events.$on('report:fiscal-years', years => {
            this.years = years
        })
    }
}
</script>