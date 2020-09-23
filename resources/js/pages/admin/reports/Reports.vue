<template>
    <div class="w-full flex flex-wrap mx-auto py-16"> 
        <div class="w-2/12 flex flex-col p-2">
            <div class="mb-4">
                <filters-type />
            </div>

            <template v-if="filters.type !== ''">
                <div class="mb-4">
                    <filters-fiscal />
                </div>

                <div class="mb-4">
                    <filters-quarter />
                </div>
            </template>
        </div>

        <template v-if="filters.type !== ''">
            <component 
                :is="resultType(filters.type)"
            ></component>
        </template>
        
        <!-- <template v-if="filters.type !== ''">
            <div 
                class="w-5/12 p-2 overflow-auto" 
                v-if="isEmpty(stats) === false"
            >
                <tables-fiscal
                    :fiscal-years="fiscalYears"
                    :fiscal-years-data="stats.overall.totals_by_year"
                />
            </div>

            <div 
                class="w-5/12 p-2 overflow-auto"
                v-if="isEmpty(stats) === false"
            >
                <tables-quarter
                    :quarters="stats.quarters"
                />
            </div>
        </template> -->
        
        
    </div>
</template>

<script>

// import Plotly from 'plotly.js-dist'
// import { map, forEach, filter, sortedUniq, flattenDepth, findIndex, orderBy, reduce, isEmpty, trimEnd } from 'lodash-es'

export default {
    data () {
        return {
            filters: {
                fiscal: [],
                quarters: [],
                type: ''
            }
        }
    },

    watch: {
        async 'filters.type' () {
            let { data } = await axios.post(`${this.urlBase}/api/admin/report/get-fiscal`, {
                type: this.filters.type
            })

            window.events.$emit('report:fiscal-years', data)
        },

        async 'filters.fiscal' () {
            let { data } = await axios.post(`${this.urlBase}/api/admin/reports`, {
                type: this.filters.type,
                quarters: this.filters.quarters,
                fiscal: this.filters.fiscal
            })
            
            window.events.$emit('report:data', data)
        },

        async 'filters.quarters' () {
            let { data } = await axios.post(`${this.urlBase}/api/admin/reports`, {
                type: this.filters.type,
                quarters: this.filters.quarters,
                fiscal: this.filters.fiscal
            })

            window.events.$emit('report:data', data)
        }
    },

    methods: {
        resultType (str) {
            str +='';

            str = str.split('_');

            for(var i=0;i<str.length;i++){ 
                str[i] = str[i].slice(0,1).toUpperCase() + str[i].slice(1,str[i].length);
            }

            return `${str.join('')}Report`;
        }
    },

    async mounted () {
        window.events.$on('report:filter-type', type => {
            this.filters.type = type
        })

        window.events.$on('report:filter-fiscal', fiscal => {
            this.filters.fiscal = fiscal
        })

        window.events.$on('report:filter-quarter', quarters => {
            this.filters.quarters = quarters
        })
    }
    // data () {
    //     return {
    //         fiscalYears: [],
    //         stats: null,
    //         filters: {
    //             fiscal: [],
    //             quarters: []
    //         },
    //         topFive: [],
    //         imgData: []
    //     }
    // },

    // watch: {
    //     filters: {
    //         deep: true,

    //         async handler () {
    //             let data = []

    //             for await (let year of this.filters.fiscal) {
    //                 if (this.filters.quarters.length === 0) {
    //                     data.push(flattenDepth(filter(this.stats.quarters, quarter => quarter.year === year), 2))
    //                 } else {
    //                     for await (let quarter of this.filters.quarters) {
    //                         data.push(flattenDepth(filter(this.stats.quarters, q => {
    //                             return q.year === year && q.quarter === quarter
    //                         }), 2))
    //                     }
    //                 }
    //             }

    //             let temp = []

    //             for await (let quarter of flattenDepth(data, 2)) {
    //                 for await (let course of quarter.data) {
    //                     let foundIndex = findIndex(temp, ['courseName', course.courseName])

    //                     if (foundIndex >= 0) {
    //                         temp[foundIndex]['views'] += course.views
    //                     } else {
    //                         temp.push({
    //                             courseName: course.courseName,
    //                             views: course.views
    //                         })
    //                     }
    //                 }
    //             }

    //             this.topFive = orderBy(temp, ['views'], 'desc').slice(0, 5)

    //             this.generate()
    //         }
    //     }
    // },

    // async mounted () {
    //     let { data } = await axios.get(`${this.urlBase}/api/admin/reports`)

    //     this.stats = data

    //     this.topFive = this.stats.overall.top_5

    //     this.fiscalYears = sortedUniq(map(data.quarters, quarter => quarter.year))

    //     window.events.$on('report:filter-fiscal', fiscal => this.filters.fiscal = fiscal)

    //     window.events.$on('report:filter-quarter', quarters => this.filters.quarters = quarters)

    //     this.generate()
    // },
    

    // methods: {
    //     isEmpty,
        
    //     async generate () {
    //         let addBreaksAtLength = 16;
            
    //         let dataX = this.topFive.map(item => item.courseName).map(text => {
    //             if (text.length > addBreaksAtLength * 3) {
    //                 text = `${text.slice(0, addBreaksAtLength * 3)}...`
    //             }

    //             let newString = ''
    //             let addBreaksAtLength = 16
    //             let textArr = text.split(' ')
    //             let arrIndex = 0

    //             for (let i = 0; i < textArr.length; i++) {
    //                 let stringLine = ''

    //                 if (arrIndex > i) {
    //                     continue
    //                 }

    //                 if (textArr[i].length >= 16) {
    //                     newString += `${newString}<br>`

    //                     arrIndex = i

    //                     continue
    //                 }

    //                 if (textArr.length === 1) {
    //                     newString += `${textArr[i]}`

    //                     break
    //                 }

    //                 for (let j = i; i < textArr.length; j++) {
    //                     if (`${stringLine} ${textArr[j]}`.length > 16 ) {
    //                         newString += `${trimEnd(stringLine)}<br>`

    //                         arrIndex = j

    //                         break
    //                     } 

    //                     stringLine += `${textArr[j]} `
    //                 }
    //             }

    //             return newString
    //         })

    //         let dataY = this.topFive.map(item => item.views)

    //         const data = [
    //             {
    //                 x: dataX,
    //                 y: dataY,
    //                 type: 'bar'
    //             }
    //         ];

    //         const layout = {
    //             title: 'Top 5 Training Portal Courses by Views',
    //         }

    //         let d3 = Plotly.d3;
    //         let img_jpg = d3.select('#jpg-export');

    //         Plotly.newPlot('myDiv', data, layout)
    //             .then(gd => {
    //                 Plotly.toImage(gd)
    //                     .then(url => {
    //                         this.imgData.push(url)

    //                         img_jpg.attr("src", url)
    //                     })
    //             })

    
    //     }
    // }
}
</script>