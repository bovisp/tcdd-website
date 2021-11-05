<template>
    <div v-if="data">
        <div 
            v-if="data.data.parts"
            class="mb-8"
        >
            <component 
                v-for="part in orderBy(data.data.parts, ['sort_order'], ['asc'])"
                :key="part.id"
                :is="`Final${ pascalCase(part.builderType.type) }`"
                :id="data.content_builder_id"
                :data="part"
            ></component>
        </div>

        <draw-component
            v-if="data.data.drawing_options"
            :background-image="data.data.drawing_options.background_image[0].file"
            :pen-colors="data.data.drawing_options.pen_colors"
            :question-id="data.id"
            :canvas-data="form.drawing"
        />

        <div 
            class="mt-8"
            v-if="data.data.text_answer"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
            >{{ trans('generic.answer') }}</label>

            <template v-if="data.data.rich_text">
                <vue-editor 
                    v-model="form.text"
                ></vue-editor>
            </template>

            <template v-else>
                <textarea 
                    class="form-textarea mt-1 block w-full" 
                    rows="10" 
                    v-model="form.text"></textarea>
            </template>
        </div>

    </div>
</template>

<script>
import { orderBy, get, debounce } from 'lodash-es'
import { pascalCase } from 'change-case'
import { mapActions, mapGetters } from 'vuex'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            form: {
                text: '',
                drawing: ''
            },
            canvasData: null
        }
    },

    computed: {
        ...mapGetters({
            attemptForm: 'assessment/form'
        })
    },

    watch: {
        'form.text': {
            handler: debounce(function (data) {
                this.updateAttemptForm({
                    id: this.data.id,
                    key: 'text',
                    data,
                    timestamp: Math.floor(new Date().getTime() / 1000)
                })
            }, 1000)
        }
    },

    methods: {
        ...mapActions({
            updateAttemptForm: 'assessment/updateAttemptForm'
        }),

        orderBy,

        pascalCase
    },

    mounted () {
        if (this.attemptForm && get(this.attemptForm, `question_${this.data.id}.drawing`)) {
            this.form.drawing = this.attemptForm[`question_${this.data.id}`]['drawing']['data']
        }

        if (this.attemptForm && get(this.attemptForm, `question_${this.data.id}.text`)) {
            this.form.text = this.attemptForm[`question_${this.data.id}`]['text']['data']
        }

        window.events.$on('draw:data', data => {
            if (this.data.id === data.id) {
                this.updateAttemptForm({
                    id: this.data.id,
                    key: 'drawing',
                    data: data.data,
                    timestamp: Math.floor(new Date().getTime() / 1000)
                })

                this.form.drawing = data.data
            }
        })
    }
}
</script>