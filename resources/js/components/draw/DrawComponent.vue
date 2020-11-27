<template>
    <div class="flex flex-col mt-16">
        <div class="mx-auto">
            <button @click.prevent="setStrokeColor('black')">Black</button>
            <button @click.prevent="setStrokeColor('blue')">Blue</button>
            <button @click.prevent="setStrokeColor('green')">Green</button>
            <button @click.prevent="setStrokeColor('purple')">Purple</button>
            <button @click.prevent="setStrokeColor('red')">Red</button>
            <button @click.prevent="isEraser = true">Eraser</button>
            <button @click.prevent="clearCanvas">Clear</button>
        </div>

        <canvas 
            id="canvas"
            class="border-2 mx-auto"
            @mousedown="startPainting" 
            @mouseup="finishedPainting"
            @mousemove="draw"
        ></canvas>
    </div>
</template>

<script>
export default {
    props: {
        backgroundImage: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            vueCanvas: null,
            painting: false,
            canvas: null,
            ctx: null,
            strokeColor: 'black',
            isEraser: false,
            mouseCoordinates: {
                x: 0,
                y: 0
            }
        }
    },

    methods: {
        clearCanvas () {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height)
        },

        setStrokeColor(color) {
            this.isEraser = false

            this.strokeColor = color
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
            this.mouseCoordinates.x = e.clientX - this.canvas.offsetLeft; 
            this.mouseCoordinates.y = e.clientY - this.canvas.offsetTop + 115; 
        }
    },

    mounted () {
        this.canvas = document.getElementById("canvas")

        this.canvas.style.backgroundImage = `url('${this.urlBase}${this.backgroundImage}')`

        this.ctx = canvas.getContext("2d")

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