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
            >Create a new question...</a>
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
export default {
     data() {
        return {
            creating: false,
            updating: false
        }
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
    }
}
</script>