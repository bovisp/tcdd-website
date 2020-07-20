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
            <div id="myDiv"></div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import toMySQLDateFormat from '../../../helpers/toMySQLDateFormat'
import Plotly from 'plotly.js-dist'

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
            ]
        }
    },

    methods: {
        toMySQLDateFormat,

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
            let { data } = await axios.post(`${this.urlBase}/api/admin/reports`, {
                type: this.type,
                from: this.form.from ? toMySQLDateFormat(this.form.from) : null,
                to: this.form.to ? toMySQLDateFormat(this.form.to) : null
            }, {responseType: 'blob'})

            const url = window.URL.createObjectURL(new Blob([data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', 'training_portal_views.xlsx')
            document.body.appendChild(link)
            link.click()

            this.cancel()

        //     let addBreaksAtLength = 20;
            
        //     let dataX = results.slice(0, 5).map(item => item.english_course_name).map(text => {
        //         let rxp = new RegExp(".{1," + addBreaksAtLength + "}", "g")

		// 	    return text.match(rxp).join("<br>")
        //     })

        //     let dataY = results.slice(0, 5).map(item => item.views)

        //     const data = [
        //         {
        //             x: dataX,
        //             y: dataY,
        //             type: 'bar'
        //         }
        //     ];

        //     Plotly.newPlot('myDiv', data);
        }
    }
}
</script>