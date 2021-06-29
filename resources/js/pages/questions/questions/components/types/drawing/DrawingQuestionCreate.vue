<template>
    <div>
        <h4 class="text-lg font-medium mb-3">
            {{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.generaloptions') }}
        </h4>

        <div
            class="mb-4"
        >
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="data.text_answer"
                >

                <span 
                    class="ml-2"
                    :class="{ 'text-red-500': errors.text_answer }"
                >{{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.requirestextanswer') }} ({{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.default') }}: <span class="font-bold">{{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.false') }}</span>)</span>
            </label>

            <p
                v-if="errors.text_answer"
                v-text="errors.text_answer[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>

        <div
            class="mb-4"
            v-if="data.text_answer"
        >
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="data.rich_text"
                >

                <span 
                    class="ml-2"
                    :class="{ 'text-red-500': errors.rich_text }"
                >{{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.answerrichtexteditor') }} ({{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.default') }}: <span class="font-bold">{{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.false') }}</span>)</span>
            </label>

            <p
                v-if="errors.rich_text"
                v-text="errors.rich_text[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>

        <h4 class="text-lg font-medium mb-2">
            {{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.drawingoptions') }}
        </h4>

        <div
            v-if="errors.drawing_options"
            v-text="errors.drawing_options[0]"
            class="alert alert-red text-sm "
        ></div>

        <h4 class="text-base font-medium mb-2 ml-4">
            {{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.pencolors') }}
        </h4>

        <div 
            class="mb-4"
        >
            <label 
                class="flex items-center ml-4"
                v-for="(color, index) in penColors"
                :key="index"
            >
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="data.drawing_options.pen_colors"
                    :value="color"
                >

                <span 
                    class="ml-2"
                >{{ ucfirst(color) }}</span>
            </label>
        </div>

        <h4 class="text-base font-medium mb-2 ml-4">
            {{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.eraser') }}
        </h4>

        <div
            class="mb-4"
        >
            <label class="flex items-center ml-4">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="data.drawing_options.eraser"
                >

                <span 
                    class="ml-2"
                >{{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.erasertool') }}</span>
            </label>
        </div>

        <h4 class="text-base font-medium mb-2 ml-4">
            {{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.cleartool') }}
        </h4>

        <div
            class="mb-4"
        >
            <label class="flex items-center ml-4">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="data.drawing_options.clear"
                >

                <span 
                    class="ml-2"
                >{{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.toolclearall') }}</span>
            </label>
        </div>

        <h4 class="text-base font-medium mb-2 ml-4">
            {{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.backgroundimage') }}
        </h4>

        <div
            class="mb-4 ml-4"
        >
            <uploader 
                :options="{
                    baseURL: '',
                    maxConcurrentUploads: 1,
                    maxFiles: 1
                }"

                :handlers="{
                    'image/gif': {
                        endpoint: '/uploads?type=image'
                    },
                    'image/jpeg': {
                        endpoint: '/uploads?type=image'
                    },
                    'image/png': {
                        endpoint: '/uploads?type=image'
                    }
                }"
                :key="rerenderUploader"
            />
        </div>

        <div class="flex justify-end">
            <button 
                class="btn btn-text btn-sm text-sm text-red-500"
                @click.prevent="forceRerender"
            >
                {{ trans('js_pages_questions_components_types_drawing_drawingquestioncreate.deleteimage') }}
            </button>
        </div>
    </div>
</template>

<script>
import ucfirst from '../../../../../../helpers/ucfirst'

export default {
    data () {
        return {
            data: {
                text_answer: false,
                rich_text: false,
                drawing_options: {
                    pen_colors: [],
                    eraser: false,
                    clear: false,
                    background_image: []
                }
            },
            penColors: [
                'white',
                'black',
                'red',
                'green',
                'blue',
                'purple',
                'brown'
            ],
            rerenderUploader: 0
        }
    },

    watch: {
        data: {
            deep: true,

            handler () {
                this.$emit('question-type:update-data', this.data)
            }
        },

        'data.text_answer' () {
            if (this.data.text_answer === false) {
                this.data.rich_text = false
            }
        }
    },

    methods: {
        ucfirst,

        async forceRerender() {
            await axios.delete(`${this.urlBase}/uploads`, {
                data: {
                    files: this.data.drawing_options.background_image
                }
            })

            this.data.drawing_options.background_image = []

            this.rerenderUploader += 1
        }
    },

    mounted () {
        this.$emit('question-type:update-data', this.data)

        window.events.$on('uploads:file', file => {
            this.data.drawing_options.background_image.push({
                file: file['file'],
                original: file['original']
            })
        })
    }
}
</script>