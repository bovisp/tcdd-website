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
            <div v-if="allUploaded">
                <div class="text-grey-700">
                    {{ trans('js_components_uploader.drophere1') }} <span class="text-blue-400">{{ trans('js_components_uploader.drophere2') }}{{ frenchFiles }} {{ pluralize(trans('js_components_uploader.files'), options.maxFiles) }}</span>
                    <template v-if="options.maxFiles === 1">
                        </br><span class="text-red-400 text-sm">{{ trans('js_components_uploader.upoloadonefile') }}.</span>
                    </template>
                </div>
            </div>
            
            <div v-else>
                {{ trans('js_components_uploader.nearlythere') }} <span class="font-bold">{{ draggingCount }}</span> {{ pluralize(trans('js_components_uploader.item'), options.maxFiles) }}!
            </div>
        </template>

        <template v-else>
           <div class="text-grey-700">
                {{ trans('js_components_uploader.drophere1') }} <span class="text-blue-400">{{ trans('js_components_uploader.drophere2') }}{{ frenchFiles }} {{ pluralize(trans('js_components_uploader.files'), options.maxFiles) }}</span>
                <template v-if="options.maxFiles === 1">
                    </br><span class="text-red-400 text-sm">{{ trans('js_components_uploader.upoloadonefile') }}.</span>
                </template>
            </div>
        </template>
    </form>
</template>

<script>
import pluralize from 'pluralize'
import options from '../options'

export default {
    props: {
        options: {
            required: false,
            type: Object,
            default: () => options
        }
    },

    data () {
        return {
            dragging: false,
            draggingCount: 0,
            allUploaded: false
        }
    },

    computed: {
        frenchFiles () {
            if (this.currentLang === 'en') {
                return ''
            }

            if (this.currentLang === 'fr' && this.options.maxFiles === 1) {
                return ' un'
            }

            return ' des'
        }
    },

    methods: {
        pluralize,

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
    },

    mounted () {
        window.events.$on('uploader:complete', () => {
            this.allUploaded = true
        })
    }
}
</script>