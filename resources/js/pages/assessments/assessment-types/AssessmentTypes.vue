<template>
    <div class="flex flex-col items-center w-full lg:w-8/12 py-16 mx-auto">
        <nav 
            class="flex justify-end w-full items-center"
            v-if="!creating && !updating"
        >
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-text"
            >Add assessment type</a>
        </nav>

        <assessment-types-create 
            v-if="creating"
        />

        <assessment-types-edit 
            v-if="updating"
        />

        <assessment-types-index 
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
        window.events.$on('assessment-types:edit', () => {
            this.updating = true
        })

        window.events.$on('assessment-types:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('assessment-types:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>