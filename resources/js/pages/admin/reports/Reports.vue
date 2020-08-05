<template>
    <div class="w-full lg:w-2/3 mx-auto py-16"> 
        <h1 class="text-3xl font-bold mb-6">
            Reports
        </h1>

        <form @submit.prevent="generate">
            <div
                class="w-full lg:w-1/2 mb-4"
            >
                <label 
                    for="type"
                    class="block text-gray-700 font-bold mb-2"
                >
                    Type
                </label>

                <div class="relative">
                    <select 
                        id="type"
                        v-model="form.type"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.type }"
                    >
                        <option value=""></option>

                        <option
                            :value="type.name"
                            v-for="type in types"
                            :key="type.name"
                            v-text="type.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.type"
                    v-text="errors.type[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="flex w-full mb-4"
            >
                <div class="w-full lg:w-1/2 pr-2">
                    <label 
                        for="from"
                        class="block text-gray-700 font-bold mb-2"
                    >
                        From <span class="font-normal">(Optional)</span>
                    </label>

                    <datepicker
                        input-class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline my-2"
                        :class="{ 'border-red-500': errors.from }"
                        v-model="form.from"
                    ></datepicker>
                </div>

                <div class="w-full lg:w-1/2 pl-2">
                    <label 
                        for="to"
                        class="block text-gray-700 font-bold mb-2"
                    >
                        To <span class="font-normal">(Optional)</span>
                    </label>

                    <datepicker
                        input-class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline my-2"
                        :class="{ 'border-red-500': errors.to }"
                        v-model="form.to"
                    ></datepicker>
                </div>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Generate report
                </button>
            </div>
        </form>

        <div class="mt-6">
            <button 
                class="btn btn-blue btn-sm text-sm mb-6"
                v-if="!isEmpty(results)"
                @click.prevent="download"
            >
                Export data as Excel file
            </button>

            <button 
                class="btn btn-blue btn-sm text-sm mb-6"
                v-if="!isEmpty(results)"
                @click.prevent="downloadImages"
            >
                Download images
            </button>

            <datatable 
                v-if="!isEmpty(results)"
                :data="results.data"
                :columns="results.columns"
                :per-page="10"
                :order-keys="results.orderKeys"
                :order-key-directions="results.orderKeyDirections"
                :has-text-filter="true"
            />

            <div id="myDiv" class="mt-6 hidden"></div>

            <img id="jpg-export" />
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import toMySQLDateFormat from '../../../helpers/toMySQLDateFormat'
import Plotly from 'plotly.js-dist'
import { trimEnd, isEmpty } from 'lodash-es'
import XLSX from 'xlsx'
import JSZip from 'jszip'
import { saveAs } from 'file-saver'

export default {
    components: {
        Datepicker
    },

    data () {
        return {
            form: {
                type: '',
                from: '',
                to: ''
            },
            types: [
                { name: 'Training Portal Course Views' }
            ],
            results: null,
            imgData: []
        }
    },

    methods: {
        toMySQLDateFormat,

        isEmpty,

        async downloadImages () {
            var zip = new JSZip();

            for await (let [index, values] of this.imgData.entries()) {
                zip.file(`imag${index}.png`, atob(this.imgData[index].split(',')[1]), {binary: true});
            }

            zip.generateAsync({type:"blob"})
                .then(function(content) {
                    saveAs(content, "example.zip");
                });
        },

        cancel () {
            this.form.type = ''
            this.form.to = ''
            this.form.from = ''
        },
        
        async generate () {
            let { data: results } = await axios.post(`${this.urlBase}/api/admin/reports`, {
                type: this.type,
                from: this.form.from ? toMySQLDateFormat(this.form.from) : null,
                to: this.form.to ? toMySQLDateFormat(this.form.to) : null
            })

            this.results = {
                columns: [
                    { field: 'categoryName', title: 'Category' },
                    { field: 'courseName', title: 'Course' },
                    { field: 'views', title: 'Views' }
                ],
                data: results,
                orderKeys: ['views'],
                orderKeyDirections: ['desc']
            }

            this.cancel()

            let addBreaksAtLength = 16;
            
            let dataX = results.slice(0, 5).map(item => item.courseName).map(text => {
                if (text.length > addBreaksAtLength * 3) {
                    text = `${text.slice(0, addBreaksAtLength * 3)}...`
                }

                let newString = ''
                let addBreaksAtLength = 16
                let textArr = text.split(' ')
                let arrIndex = 0

                for (let i = 0; i < textArr.length; i++) {
                    let stringLine = ''

                    if (arrIndex > i) {
                        continue
                    }

                    if (textArr[i].length >= 16) {
                        newString += `${newString}<br>`

                        arrIndex = i

                        continue
                    }

                    if (textArr.length === 1) {
                        newString += `${textArr[i]}`

                        break
                    }

                    for (let j = i; i < textArr.length; j++) {
                        if (`${stringLine} ${textArr[j]}`.length > 16 ) {
                            newString += `${trimEnd(stringLine)}<br>`

                            arrIndex = j

                            break
                        } 

                        stringLine += `${textArr[j]} `
                    }
                }

                return newString
            })

            let dataY = results.slice(0, 5).map(item => item.views)

            let d3 = Plotly.d3;
            let img_jpg = d3.select('#jpg-export');

            const data = [
                {
                    x: dataX,
                    y: dataY,
                    type: 'bar'
                }
            ];

            const layout = {
                title: 'Top 5 Training Portal Courses by Views',
            }

            Plotly.newPlot('myDiv', data, layout)
                .then(gd => {
                    Plotly.toImage(gd)
                        .then(url => {
                            this.imgData.push(url)

                            img_jpg.attr("src", url)
                        })
                });
        },

        download () {
            let sheetData = XLSX.utils.json_to_sheet(this.results.data)

            let workbook = XLSX.utils.book_new() 

            XLSX.utils.book_append_sheet(workbook, sheetData, 'Binary values')

            XLSX.writeFile(workbook, 'text.xlsx')
        }
    }
}
</script>