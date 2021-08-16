<template>
    <div v-if="form.drawing_options">
        <h4 class="subtitle is-5">
            {{ trans('generic.generaloptions') }}
        </h4>

        <b-field class="ml-4">
            <b-checkbox
                type="is-info"
                v-model="form.text_answer"
                :true-value="1"
                :false-value="0"
            >
                <span 
                    :class="{ 'has-text-danger': errors.text_answer }"
                >{{ trans('generic.requirestextanswer') }} ({{ trans('generic.default') }}: <span class="font-bold">{{ trans('generic.false') }}</span>)</span>
            </b-checkbox>

            <p
                v-if="errors.text_answer"
                v-text="errors.text_answer[0]"
                class="has-text-danger text-sm"
            ></p>
        </b-field>

        <b-field 
            class="ml-4"
            v-if="form.text_answer"
        >
            <b-checkbox
                type="is-info"
                v-model="form.rich_text"
                :true-value="1"
                :false-value="0"
            >
                <span 
                    :class="{ 'has-text-danger': errors.rich_text }"
                >{{ trans('generic.answerrichtexteditor') }} ({{ trans('generic.default') }}: <span class="font-bold">{{ trans('generic.false') }}</span>)</span>
            </b-checkbox>

            <p
                v-if="errors.rich_text"
                v-text="errors.rich_text[0]"
                class="has-text-danger text-sm"
            ></p>
        </b-field>

        <h4 class="subtitle is-5">
            {{ trans('generic.drawingoptions') }}
        </h4>

        <b-message
            v-if="errors.drawing_options"
            type="is-danger"
        >
            {{ errors.drawing_options[0] }}
        </b-message>

        <h5 class="font-bold text-normal mb-2">
            {{ trans('generic.pencolors') }}
        </h5>

        <b-field class="ml-4">
            <b-checkbox
                type="is-info"
                v-model="form.drawing_options.pen_colors"
                v-for="(color, index) in penColors"
                :key="index"
                :native-value="color"
            >
                {{ sentenceCase(color) }}
            </b-checkbox>
        </b-field>

        <h5 class="font-bold text-normal mb-2">
            {{ trans('generic.eraser') }}
        </h5>

        <b-field class="ml-4">
            <b-checkbox
                type="is-info"
                v-model="form.drawing_options.eraser"
                :true-value="1"
                :false-value="0"
            >
                {{ trans('generic.erasertool') }}
            </b-checkbox>
        </b-field>

        <h5 class="font-bold text-normal mb-2">
            {{ trans('generic.cleartool') }}
        </h5>

        <b-field class="ml-4">
            <b-checkbox
                type="is-info"
                v-model="form.drawing_options.clear"
                :true-value="1"
                :false-value="0"
            >
                {{ trans('generic.toolclearall') }}
            </b-checkbox>
        </b-field>

        <h5 class="font-bold text-normal mb-2">
            {{ trans('generic.backgroundimage') }}
        </h5>

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
                v-if="form.drawing_options"
                :src="`${urlBase}${form.drawing_options.background_image[0].file}`"
            >
        </template>

        <div class="flex mt-2">
            <button 
                class="btn btn-text btn-sm text-sm text-red-500"
                @click.prevent="forceRerender"
            >
                {{ trans('generic.deleteimage') }}
            </button>
        </div>
    </div>
</template>

<script>
import { sentenceCase } from 'change-case'
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
            if (this.form.text_answer === 0) {
                this.form.rich_text = 0
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
        sentenceCase,

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
        window.events.$on('uploads:file', file => {
            this.form.drawing_options.background_image.push({
                file: file['file'],
                original: file['original']
            })
        })
    }
}
</script>