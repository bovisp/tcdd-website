<template>
    <div class="flex flex-col items-center w-full md:w-2/3 lg:w-1/2 py-16 mx-auto">
        <nav 
            class="flex justify-end w-full items-center"
            v-if="!creating && !updating"
        >
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-text"
            >Create question type</a>
        </nav>

        <question-types-create 
            v-if="creating"
        />

        <question-types-edit 
            v-if="updating"
        />

        <question-types-index 
            v-if="!creating && !updating"
        />
    </div>
</template>

<script>
export default {
     data() {
        return {
            creating: false,
            updating: false
        }
    },

    mounted () {
        window.events.$on('question-types:edit', () => {
            this.updating = true
        })

        window.events.$on('question-types:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('question-types:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>