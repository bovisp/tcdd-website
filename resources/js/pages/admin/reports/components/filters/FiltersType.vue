<template>
    <div>
        <strong class="block font-bold text-md mb-2">
            {{ trans('js_pages_admin_reports_components_filters_filterstype.type') }}
        </strong>

        <label 
            class="block mb-1"
            id="quarter"
            v-for="t in types"
            :key="t.code"
        >
            <input 
                type="radio" 
                class="form-radio h-5 w-5 text-gray-600"
                :value="t"
                v-model="type"
            >
                <span class="ml-2 text-gray-700">{{ t.name }}</span>
        </label>
    </div>
</template>

<script>
export default {
    data () {
        return {
            type: null,
            types: [
                { code: 'training_portal', name: this.trans('js_pages_admin_reports_components_filters_filterstype.trainingportal') },
                { code: 'comet', name: this.trans('js_pages_admin_reports_components_filters_filterstype.comet') },
                { code: 'stay_connected', name: this.trans('js_pages_admin_reports_components_filters_filterstype.stayconnected') }
            ]
        }
    },

    watch: {
        type () {
            window.events.$emit('report:filter-type', this.type.code)
        }
    }
}
</script>