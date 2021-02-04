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

        <ul v-if="data.data.answers">
            <li
                v-for="answer in shuffleArray(data.data.answers)"
                :key="answer.id"
                class="mb-2"
            >
                <label class="flex items-center">
                    <input 
                        :type="data.data.multiple_answers ? 'checkbox' : 'radio'" 
                        :class="data.data.multiple_answers ? 'form-checkbox' : 'form-radio'"
                        v-model="form.answers"
                        :value="answer.id"
                    >

                    <span 
                        class="ml-2"
                    >{{ answer.text }}</span>
                </label>
            </li>
        </ul>
    </div>
</template>

<script>
import { orderBy, get } from 'lodash-es'
import { pascalCase } from 'change-case'
import { mapGetters, mapActions } from 'vuex'

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
                answers: []
            }
        }
    },

    computed: {
        ...mapGetters({
            attemptForm: 'assessment/form'
        })
    },

    watch: {
        'form.answers' () {
            this.updateAttemptForm({
                id: this.data.id,
                key: 'answers',
                data: this.form.answers,
                timestamp: Math.floor(new Date().getTime() / 1000)
            })
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
        if (this.attemptForm && get(this.attemptForm, `question_${this.data.id}.answers`)) {
            this.form.answers = this.attemptForm[`question_${this.data.id}`]['answers']['data']
        }
    }
}
</script>