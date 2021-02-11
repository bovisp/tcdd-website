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
                @click.prevent="clearCanvasConfirm"
                class="border rounded-xl px-3 py-2 mt-3"
                title="Erase all work"
            >
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <canvas 
            :id="`canvas${canvasId}`"
            ref="canvas"
            class="border-2 mx-auto"
            @mousedown="startPainting" 
            @mouseup="finishedPainting"
            @mousemove="draw"
        ></canvas>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="clearCanvas"
        >
            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        Are you sure you want to erase all of your work? 
                        This operation cannot be undone.
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import ucfirst from '../../helpers/ucfirst'
import VSwatches from 'vue-swatches'
import uuidv4 from '../../helpers/uuidv4'

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
        },
        canvasData: {
            type: String,
            required: false
        },
        questionId: {
            type: Number,
            required: false
        }
    },

    data () {
        return {
            strokeColor: this.penColors[0],
            canvasId: uuidv4(),
            painting: false,
            canvas: null,
            ctx: null,
            isEraser: false,
            mouseCoordinates: {
                x: 0,
                y: 0
            },
            modalActive: false
        }
    },

    watch: {
        strokeColor () {
            this.isEraser = false
        }
    },

    methods: {
        ucfirst,

        close () {
            this.modalActive = false
        },

        clearCanvasConfirm () {
            this.modalActive = true
        },

        clearCanvas () {
            this.modalActive = false

            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height)

            if (this.questionId) {
                window.events.$emit('draw:data', {
                    data: this.canvas.toDataURL(),
                    id: this.questionId
                })
            }
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

            if (this.questionId) {
                window.events.$emit('draw:data', {
                    data: this.canvas.toDataURL(),
                    id: this.questionId
                })
            }
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

            if (that.questionId && that.canvasData) {
                let img = new Image()

                img.onload = function(){
                    that.ctx.drawImage(img,0,0)
                }

                img.src = that.canvasData
            }
        }

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