<template>
    <div class="w-full">
        <div class="flex w-full mb-4" v-if="hasTextFilter">
            <input 
                type="text" 
                v-model="textFilter"
                class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                placeholder="Search..."
                @keydown.esc="textFilter = ''"
            >

            <slot></slot>
        </div>

        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-300">
                    <th 
                        v-if="checkable"
                        class="p-1"
                    ></th>

                    <th
                        v-for="column in columns"
                        :key="column.field"
                        v-text="column.title"
                        class="text-left p-1"
                    ></th>

                    <th 
                        v-if="hasAction || hasEvent"
                        class="p-1"
                    ></th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="item in filteredData"
                    :key="item.id"
                    class="border-b border-gray-300"
                >
                    <td v-if="checkable" class="p-1">
                        <input 
                            type="checkbox" 
                            :value="item.id"
                            v-model="selected"
                        >
                    </td>
                    
                    <td
                        v-for="column in columns"
                        :key="column.field"
                        v-text="item[column.field]"
                        class="p-1"
                    ></td>

                    <td v-if="hasAction || hasEvent" class="p-1">
                        <template v-if="hasAction">
                            <a 
                                :href="`${actionLink}/${item[actionId]}`"
                                class="btn btn-text text-sm"
                            >
                                {{ actionText }}
                            </a>
                        </template>
                        
                        <template v-if="hasEvent">
                            <button 
                                @click.prevent="emitEvent(event, item)"
                                class="btn btn-text text-sm text-blue-500"
                            >
                                {{ eventText }}
                            </button>
                        </template>
                    </td>
                </tr>
            </tbody>
        </table>

        <paginate
            :page-count="pages"
            :click-handler="paginate"
            :container-class="'paginate'"
        />
    </div>
</template>

<script>
import paginate from 'vuejs-paginate'
import { orderBy, filter, forEach, find, map } from 'lodash-es'

export default {
    components: {
        paginate
    },

    props: {
        data: {
            type: Array,
            required: true
        },
        columns: {
            type: Array,
            required: true
        },
        perPage: {
            type: Number,
            required: false,
            default: 10
        },
        orderKeys: {
            type: Array,
            required: false,
            default: () => []
        },
        orderKeyDirections: {
            type: Array,
            required: false,
            default: () => []
        },
        hasTextFilter: {
            type: Boolean,
            required: false,
            default: false
        },
        checkable: {
            type: Boolean,
            required: false,
            default: false
        },
        dropdown: {
            type: Array,
            required: false,
            default: () => []
        },
        selectedValues: {
            type: Array,
            required: false,
            default: () => []
        },
        hasAction: {
            type: Boolean,
            required: false,
            default: false
        },
        actionText: {
            type: String,
            required: false,
            default: ''
        },
        actionLink: {
            type: String,
            required: false,
            default: '#'
        },
        actionId: {
            type: String,
            required: false,
            default: 'null'
        },
        event: {
            type: String,
            required: false,
            default: ''
        },
        eventText: {
            type: String,
            required: false,
            default: ''
        },
        hasEvent: {
            type: Boolean,
            required: false,
            default: false
        },
        selectedItems: {
            type: Array,
            required: false,
            default: () => []
        }
    },

    data() {
        return {
            currentPage: 1,
            textFilter: '',
            pages: 1,
            selected: this.selectedItems
        }
    },

    computed: {
        filteredData () {  
            let filtered = this.data
            if (this.orderKeys.length) {
                filtered = orderBy(
                    filtered, this.orderKeys, this.orderKeyDirections
                )
            }
            if (this.textFilter) {
                filtered = filter(filtered, item => {
                    let isIndexOf = false
                    forEach(this.columns, column => {
                        if (this.matches(item[column.field])) {
                            isIndexOf = true
                        }
                    })
                    return isIndexOf
                })
            }
            this.pages = Math.ceil(filtered.length / this.perPage)
            return filter(filtered, (item, index) => {
                return index >= this.startIndex && index < this.endIndex
            })
        },

        startIndex () {
            return (this.currentPage - 1) * this.perPage
        },

        endIndex () {
            return this.startIndex + this.perPage
        }
    },

    watch: {
        selected () {
            window.events.$emit('users:selected', this.selected)
        }
    },

    methods: {
        matches (item) {
            return item.toString().toLowerCase().indexOf(this.textFilter.toLowerCase()) >= 0
        },

        paginate (page) {
            this.currentPage = page
        },

        reset () {
            this.selected = []
            this.currentPage = 1
            this.textFilter = ''
            this.pages = 1
        },

        emitEvent(event, item) {
            window.events.$emit(event, item)
        }
    },

    mounted () {
        if (this.selectedValues.length) {
            this.selected = this.selectedValues
        }

        window.events.$on('datatable:clear', () => {
            this.reset()
        })

        window.events.$on('datatable:cancel', () => {
            this.reset()
        })

        window.events.$on('datatable:reload-selected', selected => {
            this.selected = selected
        })
    }
}
</script>