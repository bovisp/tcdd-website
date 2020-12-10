<template>
    <div>
        <div class="flex items-end">
            <div class="mr-auto">
                <button 
                    class="btn btn-blue"
                    @click.prevent="add"
                >
                    <i class="fas fa-plus mr-1"></i>
                    Add page
                </button>
            </div>
            
            <div v-if="pages.length !== 0">
                <label 
                    for="content_type"
                    class="block text-gray-700 font-bold mb-2"
                >
                    Choose a page...
                </label>

                <div class="relative">
                    <select 
                        id="content_type"
                        v-model="page"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    >
                        <option
                            :value="p.number"
                            v-for="p in orderedPages"
                            :key="p.id"
                            v-text="`Page ${p.number}`"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <assessment-page 
            v-if="page"
            :page="find(pages, p => p.number = page)"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { orderBy, find } from 'lodash-es'

export default {
    data () {
        return {
            pages: [],
            page: null
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        }),

        orderedPages () {
            return orderBy(this.pages, ['number'], ['asc'])
        }
    },

    methods: {
        find,
        
        async add () {
            let { data } = await axios.post(`${this.urlBase}/api/assessments/${this.assessment.id}/page`)

            this.pages.push(data.data)

            this.page = data.data.number
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/assessments/${this.assessment.id}/page`)

        this.pages = data.data

        this.page = this.orderedPages[0].number
    }
}
</script>