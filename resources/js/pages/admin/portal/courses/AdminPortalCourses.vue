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
            >Add course</a>
        </nav>

        <admin-portal-courses-create 
            v-if="creating"
        />

        <admin-portal-courses-edit 
            v-if="updating"
        />

        <admin-portal-courses-index 
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
        window.events.$on('portal-courses:edit', () => {
            this.updating = true
        })

        window.events.$on('portal-courses:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('portal-courses:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>