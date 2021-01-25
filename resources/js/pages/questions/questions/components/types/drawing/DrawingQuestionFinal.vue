<template>
    <div class="data">
        <div 
            v-if="data.data.parts"
            class="mb-8"
        >
            <component 
                v-for="part in orderBy(data.data.parts, ['sort_order'], ['asc'])"
                :key="part.id"
                :is="`Final${ pascalCase(part.builderType.type) }`"
                :part="part"
            ></component>
        </div>

        <draw-component
            v-if="data.data.drawing_options"
            :background-image="data.data.drawing_options.background_image[0].file"
            :pen-colors="data.data.drawing_options.pen_colors"
        />

        <div 
            class="mt-8"
            v-if="data.data.text_answer"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
            >Answer</label>

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
import { orderBy } from 'lodash-es'
import { pascalCase } from 'change-case'

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
                text: ''
            }
        }
    },

    methods: {
        orderBy,

        pascalCase
    }
}
</script>