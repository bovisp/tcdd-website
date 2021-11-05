<template>
    <div class="flex flex-col items-center w-full py-16 mx-auto">
        <nav 
            class="flex justify-end w-full items-center"
            v-if="!creating && !updating"
        >
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-text"
            >{{ trans('js_pages_questions_questions_questions.createnewquestions') }}...</a>
        </nav>

        <questions-create 
            v-if="creating"
        />

        <questions-edit 
            v-if="updating"
        />

        <questions-index 
            v-if="!creating && !updating"
        />
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
     data() {
        return {
            creating: false,
            updating: false
        }
    },

    methods: {
        ...mapActions({
            fetchAvailableEditors: 'questions/fetchAvailableEditors',
            fetchSections: 'sections/fetch',
            fetchQuestionCategories: 'questionCategories/fetch',
            fetchTags: 'tags/fetch',
            fetchQuestionTypeData: 'questions/fetchQuestionTypeData',
            fetchQuestion: 'questions/setEdit'
        }),
    },

    mounted () {
        window.events.$on('questions:edit', () => {
            this.updating = true
        })

        window.events.$on('questions:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('questions:create-cancel', () => {
            this.creating = false
        })

        window.events.$on('questions:edit', async (question) => {
            await this.fetchQuestion(question.id)
            await this.fetchAvailableEditors(question.id)
            await this.fetchSections()
            await this.fetchQuestionCategories()
            await this.fetchTags()
            await this.fetchQuestionTypeData(question.id)
        })
    }
}
</script>