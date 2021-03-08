<template>
    <div
        v-if="typeof form !== 'undefined'"
    >
        <h4 class="text-lg font-medium mb-3">
            General options
        </h4>

        <div
            class="mb-4"
        >
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="form.text_answer"
                >

                <span 
                    class="ml-2"
                    :class="{ 'text-red-500': errors.text_answer }"
                >Requires a text answer as well (default: <span class="font-bold">false</span>)</span>
            </label>

            <p
                v-if="errors.text_answer"
                v-text="errors.text_answer[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>

        <div
            class="mb-4"
            v-if="form.text_answer"
        >
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="form.rich_text"
                >

                <span 
                    class="ml-2"
                    :class="{ 'text-red-500': errors.rich_text }"
                >Answer can have a rich text editor (default: <span class="font-bold">false</span>)</span>
            </label>

            <p
                v-if="errors.rich_text"
                v-text="errors.rich_text[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>

        <h4 class="text-lg font-medium mb-2">
            Drawing options
        </h4>

        <div
            v-if="errors.drawing_options"
            v-text="errors.drawing_options[0]"
            class="alert alert-red text-sm "
        ></div>

        <h4 class="text-base font-medium mb-2 ml-4">
            Pen colors
        </h4>

        <div 
            class="mb-4"
            v-if="typeof form.drawing_options !== 'undefined'"
        >
            <label 
                class="flex items-center ml-4"
                v-for="(color, index) in penColors"
                :key="index"
            >
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="form.drawing_options.pen_colors"
                    :value="color"
                >

                <span 
                    class="ml-2"
                >{{ ucfirst(color) }}</span>
            </label>
        </div>

        <h4 class="text-base font-medium mb-2 ml-4">
            Eraser
        </h4>

        <div
            class="mb-4"
            v-if="typeof form.drawing_options !== 'undefined'"
        >
            <label class="flex items-center ml-4">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="form.drawing_options.eraser"
                >

                <span 
                    class="ml-2"
                >Eraser tool</span>
            </label>
        </div>

        <h4 class="text-base font-medium mb-2 ml-4">
            Clear tool
        </h4>

        <div
            class="mb-4"
            v-if="typeof form.drawing_options !== 'undefined'"
        >
            <label class="flex items-center ml-4">
                <input 
                    type="checkbox" 
                    class="form-checkbox"
                    v-model="form.drawing_options.clear"
                >

                <span 
                    class="ml-2"
                >Tool to clear all drawing work</span>
            </label>
        </div>

        <h4 class="text-base font-medium mb-2 ml-4">
            Background image
        </h4>

        <template v-if="rerenderUploader !== 0">
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
        </template>

        <template v-else>
            <img 
                class="ml-4"
                v-if="typeof form.drawing_options !== 'undefined'"
                :src="`${this.urlBase}${form.drawing_options.background_image[0].file}`"
            >
        </template>

        <div class="flex justify-end">
            <button 
                class="btn btn-text btn-sm text-sm text-red-500"
                @click.prevent="forceRerender"
            >
                Delete current image
            </button>
        </div>
    </div>
</template>

<script>
import ucfirst from '../../../../../../helpers/ucfirst'
import { mapGetters } from 'vuex'

export default {
    data () {
        return {
            form: {},
            penColors: [
                'white', 'black', 'red', 'green', 'blue', 'purple', 'brown'
            ],
            rerenderUploader: 0
        }
    },

    computed: {
        ...mapGetters({
            questionTypeData: 'questions/questionTypeData'
        })
    },

    watch: {
        'form.text_answer' () {
            if (this.form.text_answer === false) {
                this.form.rich_text = false
            }
        },

        form: {
            deep: true,

            handler () {
                this.$emit('question-type:update-data', this.form)
            }
        },

        questionTypeData: {
            deep: true,

            handler () {
                this.form = this.questionTypeData.data
            }
        }
    },

    methods: {
        ucfirst,

        async forceRerender() {
            await axios.delete(`${this.urlBase}/uploads`, {
                data: {
                    files: this.form.drawing_options.background_image
                }
            })

            this.form.drawing_options.background_image = []

            this.rerenderUploader += 1
        }
    },

    mounted () {
        this.form = this.questionTypeData.data

        this.$emit('question-type:update-data', this.form)

        window.events.$on('uploads:file', file => {
            this.form.drawing_options.background_image.push({
                file: file['file'],
                original: file['original']
            })
        })
    }
}
</script>