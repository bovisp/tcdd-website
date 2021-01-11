<template>
    <div class="flex">
        <div class="flex flex-col items-center">
            <p class="font-medium">Pen</p>

            <v-swatches
                v-model="strokeColor"
                :swatches="penColors"
                row-length="3"
                show-border
                popover-x="right"
            ></v-swatches>

            <button 
                @click.prevent="isEraser = true"
                class="border rounded-xl px-3 py-2 mt-3"
                title="Eraser tool"
            >
                <i class="fas fa-eraser"></i>
            </button>

            <button 
                @click.prevent="clearCanvas"
                class="border rounded-xl px-3 py-2 mt-3"
                title="Erase all work"
            >
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <canvas 
            id="canvas"
            ref="canvas"
            class="border-2 mx-auto"
            @mousedown="startPainting" 
            @mouseup="finishedPainting"
            @mousemove="draw"
        ></canvas>
    </div>
</template>

<script>
import ucfirst from '../../helpers/ucfirst'
import VSwatches from 'vue-swatches'

export default {
    components: { 
        VSwatches 
    },

    props: {
        backgroundImage: {
            type: String,
            required: true
        },
        penColors: {
            type: Array,
            required: false,
            default: () => ['black']
        }
    },

    data () {
        return {
            strokeColor: this.penColors[0],
            vueCanvas: null,
            painting: false,
            canvas: null,
            ctx: null,
            isEraser: false,
            mouseCoordinates: {
                x: 0,
                y: 0
            }
        }
    },

    watch: {
        strokeColor () {
            this.isEraser = false
        }
    },

    methods: {
        ucfirst,

        clearCanvas () {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height)
        },

        getClientOffset (e) {
            const { pageX, pageY } = e

            const x = pageX - this.canvas.offsetLeft
            const y = pageY - this.canvas.offsetTop

            return {
                x,
                y
            } 
        },

        startPainting(e) {
            this.painting = true

            this.getPosition(e);
        },

        finishedPainting() {
            this.painting = false
        },

        draw(e) {
            if(!this.painting) return

            this.ctx.beginPath(); 

            if (this.isEraser === false) {
                this.ctx.globalCompositeOperation="source-over";
                this.ctx.lineWidth = 5
                this.ctx.lineCap = 'round' 
                this.ctx.strokeStyle = this.strokeColor

                this.ctx.moveTo(this.mouseCoordinates.x, this.mouseCoordinates.y)

                this.getPosition(e);

                this.ctx.lineTo(this.mouseCoordinates.x, this.mouseCoordinates.y)
                this.ctx.stroke()
            } else {
                this.ctx.globalCompositeOperation="destination-out";
                this.ctx.arc(this.mouseCoordinates.x,this.mouseCoordinates.y,8,0,Math.PI*2,false);
                this.getPosition(e);
                this.ctx.fill();
            }
        },

        getPosition (e) {
            let rect = this.canvas.getBoundingClientRect()

            this.mouseCoordinates.x = e.clientX - rect.left 
            this.mouseCoordinates.y = e.clientY - rect.top
        }
    },

    mounted () {
        this.canvas = this.$refs.canvas

        this.canvas.style.backgroundImage = `url('${this.urlBase}${this.backgroundImage}')`

        this.ctx = this.canvas.getContext("2d")

        let background = new Image()

        background.src = `${this.urlBase}${this.backgroundImage}`

        let that = this

        background.onload = function() {
            that.canvas.height = this.height
            that.canvas.width = this.width
        }

        this.vueCanvas = this.ctx

        window.events.$on('draw:save', async () => {
            let can2 = document.createElement('canvas')

            can2.width = this.canvas.height
            can2.height = this.canvas.width

            let ctx2 = can2.getContext("2d")

            ctx2.drawImage(this.canvas, 0, 0)

            this.ctx.clearRect(0, 0, can2.width, can2.height)

            this.ctx.drawImage(background, 0, 0)

            this.ctx.drawImage(can2, 0, 0)

            let dataURL = this.canvas.toDataURL('image/jpeg', 1.0)

            let formData = new FormData()

            formData.append('drawing', dataURL)

            let { data } = await axios.post('/uploads/drawing', formData)
            
            window.events.$emit('draw:saved', data.file)
        })
    }
}
</script>