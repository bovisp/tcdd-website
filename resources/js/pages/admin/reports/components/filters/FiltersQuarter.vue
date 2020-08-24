<template>
    <div>
        <strong class="block font-bold text-md mb-2">
            Quarter
        </strong>

        <label 
            class="block mb-1"
            id="quarter"
            v-for="q in 4"
            :key="q"
        >
            <input 
                type="checkbox" 
                class="form-checkbox h-5 w-5 text-gray-600"
                name="quarter"
                :value="q"
                v-model="quarter"
            >
                <span class="ml-2 text-gray-700">Q{{ q }}</span>
        </label>
    </div>
</template>

<script>
import { sortedUniq } from 'lodash-es'

export default {
    data () {
        return {
            quarter: []
        }
    },

    watch: {
        quarter () {
            window.events.$emit('report:filter-quarter', this.quarter)
        }
    }
}
</script>