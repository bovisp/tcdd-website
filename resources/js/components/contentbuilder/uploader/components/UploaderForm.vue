<template>
    <form 
        enctype="multipart/form-data"
        novalidate
        class="bg-gray-100 rounded p-12 flex justify-center items-center mb-6 relative"
        :style="[ dragging ? {'borderColor': 'Gray'} : {'borderColor': 'LightGray'}]"
        style="border: 2px dashed;"
        @dragover.prevent="handleDragOver"
        @dragleave.prevent="handleDragLeave"
    >
        <input 
            type="file"
            multiple
            class="absolute w-full h-full"  
            style="opacity: 0; top: 0; left: 0;"
            @change="handleFilesChosen"
        >

        <template v-if="draggingCount">
            <div>
                Nearly there. Let go to upload <span class="font-bold">{{ draggingCount }}</span> items!
            </div>
        </template>

        <template v-else>
           <div class="text-grey-700">
                Drop here to upload or <span class="text-blue-400">choose files</span>
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