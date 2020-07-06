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
            >Add language</a>
        </nav>

        <admin-portal-languages-create 
            v-if="creating"
        />

        <admin-portal-languages-edit 
            v-if="updating"
        />

        <admin-portal-languages-index 
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
        window.events.$on('portal-languages:edit', () => {
            this.updating = true
        })

        window.events.$on('portal-languages:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('portal-languages:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>