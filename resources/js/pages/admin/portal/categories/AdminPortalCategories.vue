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
            >Add category</a>
        </nav>

        <admin-portal-categories-create 
            v-if="creating"
        />

        <admin-portal-categories-edit 
            v-if="updating"
        />

        <admin-portal-categories-index 
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
        window.events.$on('portal-categories:edit', () => {
            this.updating = true
        })

        window.events.$on('portal-categories:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('portal-categories:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>