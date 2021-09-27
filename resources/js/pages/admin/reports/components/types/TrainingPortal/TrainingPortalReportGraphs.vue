<template>
    <div>
        <div class="mb-4">
            <img 
                id="top5-jpg" 
                class="block w-full"
            />

            <div 
                id="top5-original" 
                class="w-full hidden"
            ></div>
        </div>

        <div>
            <img 
                id="totals-jpg" 
                class="block w-full"
            />

            <div 
                id="totals-original" 
                class="w-full hidden"
            ></div>
        </div>
    </div>
</template>

<script>
// import Plotly from 'plotly.js-dist'
// import { map, trimEnd } from 'lodash-es'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            stats: {},
            addBreaksAtLength: 16,
            topFiveImgData: [],
            totalsImgData: []
        }
    },

    watch: {
        stats: {
            deep: true,

            handler () {
                this.createTopFiveGraph()

                this.createTotalsGraph()
            }
        }
    },

    methods: {
        createTotalsGraph () {
            let dataX = [this.trans('js_pages_admin_reports_components_types_trainingportal_trainingportalreportgraphs.courseviews')]

            let dataY = [this.stats.totals.totals]

            let data = [
                {
                    x: dataX,
                    y: dataY,
                    type: 'bar',
                    width: [0.15]
                }
            ]

            let layout = {
                title: this.trans('js_pages_admin_reports_components_types_trainingportal_trainingportalreportgraphs.graphtitletotals'),
            }

            let d3 = Plotly.d3

            let imgJpg = d3.select('#totals-jpg')

            Plotly.newPlot('totals-original', data, layout)
                .then(gd => {
                    Plotly.toImage(gd)
                        .then(url => {
                            this.totalsImgData.push(url)

                            imgJpg.attr("src", url)
                        })
                })
        },

        createTopFiveGraph () {
            let dataX = this.stats.topFive.map(item => item.courseName).map(text => {
                    if (text.length > this.addBreaksAtLength * 3) {
                        text = `${text.slice(0, this.addBreaksAtLength * 3)}...`
                    }

                    let newString = ''
                    let textArr = text.split(' ')
                    let arrIndex = 0

                    for (let i = 0; i < textArr.length; i++) {
                        let stringLine = ''

                        if (arrIndex > i) {
                            continue
                        }

                        if (textArr[i].length >= this.addBreaksAtLength) {
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

                let dataY = this.stats.topFive.map(item => item.views)

                let data = [
                    {
                        x: dataX,
                        y: dataY,
                        type: 'bar'
                    }
                ]

                let layout = {
                    title: this.trans('js_pages_admin_reports_components_types_trainingportal_trainingportalreportgraphs.graphtitletopfive'),
                }

                let d3 = Plotly.d3

                let imgJpg = d3.select('#top5-jpg')

                Plotly.newPlot('top5-original', data, layout)
                    .then(gd => {
                        Plotly.toImage(gd)
                            .then(url => {
                                this.topFiveImgData.push(url)

                                imgJpg.attr("src", url)
                            })
                    })
        }
    },

    mounted () {
        this.stats = this.data

        window.events.$on('report:data', stats => {
            this.stats = stats
        })
    }
}
</script>