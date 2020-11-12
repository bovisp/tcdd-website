<template>
    <form 
        enctype="multipart/form-data"
        novalidate
        class="bg-light rounded-lg p-5 d-flex justify-content-center align-items-center mb-4 position-relative"
        :style="[ dragging ? {'borderColor': 'Gray'} : {'borderColor': 'LightGray'}]"
        style="border: 2px dashed;"
        @dragover.prevent="handleDragOver"
        @dragleave.prevent="handleDragLeave"
    >
        <input 
            type="file"
            multiple
            class="position-absolute w-100 h-100"  
            style="opacity: 0; top: 0; left: 0;"
            @change="handleFilesChosen"
        >

        <template v-if="draggingCount">
            <div>
                Nearly there. Let go to upload <span class="font-weight-bolder">{{ draggingCount }}</span> items!
            </div>
        </template>

        <template v-else>
           <div class="text-muted">
                Drop here to upload or <span class="text-info">choose files</span>
            </div>
        </template>
    </form>
</template>

<script>
export default {
    data () {
        return {
            dragging: false,
            draggingCount: 0
        }
    },

    methods: {
        handleFilesChosen (e) {
            this.dragging = false
            this.$emit('chosen', e.target.files)
        },

        handleDragOver (e) {
            this.dragging = true

            this.draggingCount = e.dataTransfer.items.length
        },

        handleDragLeave (e) {
            this.dragging = false
        }
    }
}
</script>