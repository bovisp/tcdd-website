<template>
    <div
        class="mb-4"
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
            >{{ trans('js_pages_questions_questions_components_types_essay_essayquestioncreate.answerrichtexteditor') }} ({{ trans('js_pages_questions_questions_components_types_essay_essayquestioncreate.default') }}: <span class="font-bold">{{ trans('js_pages_questions_questions_components_types_essay_essayquestioncreate.false') }}</span>)</span>
        </label>

        <p
            v-if="errors.rich_text"
            v-text="errors.rich_text[0]"
            class="text-red-500 text-sm"
        ></p>
    </div>
</template>

<script>
export default {
    data () {
        return {
            data: {
                rich_text: false
            }
        }
    },

    watch: {
        data: {
            deep: true,

            handler () {
                this.$emit('question-type:update-data', this.data)
            }
        }
    },

    mounted () {
        this.$emit('question-type:update-data', this.data)
    }
}
</script>