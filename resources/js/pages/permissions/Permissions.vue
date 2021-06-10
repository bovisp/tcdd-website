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
            >{{ trans('js_pages_permissions_permissions.addpermission') }}</a>
        </nav>

        <permissions-create 
            v-if="creating"
        />

        <permissions-edit 
            v-if="updating"
        />

        <permissions-index 
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
        window.events.$on('permissions:edit', () => {
            this.updating = true
        })

        window.events.$on('permissions:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('permissions:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>