<template>
    <div
        class="mb-4"
        v-if="typeof form !== 'undefined'"
    >
        <label class="flex items-center">
            <input 
                type="checkbox" 
                class="form-checkbox"
                v-model="form.rich_text"
                :checked="form.rich_text"
            >

            <span 
                class="ml-2"
                :class="{ 'text-red-500': errors.rich_text }"
            >{{ trans('generic.answerrichtexteditor') }} ({{ trans('generic.default') }}: <span class="font-bold">{{ trans('generic.false') }}</span>)</span>
        </label>

        <p
            v-if="errors.rich_text"
            v-text="errors.rich_text[0]"
            class="text-red-500 text-sm"
        ></p>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data () {
        return {
            form: {}
        }
    },

    computed: {
        ...mapGetters({
            questionTypeData: 'questions/questionTypeData'
        })
    },

    watch: {
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

    mounted () {
        this.form = this.questionTypeData.data

        this.$emit('question-type:update-data', this.form)
    }
}
</script>