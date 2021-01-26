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
                :part="part"
            ></component>
        </div>

        <div 
            class="mt-8"
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
                text: ''
            }
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
                    data
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
        if (this.attemptForm && get(this.attemptForm, `question_${this.data.id}.text`)) {
            this.form.text = this.attemptForm[`question_${this.data.id}`]['text']
        }
    }
}
</script>