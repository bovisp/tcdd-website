<template>
    <div class="flex flex-col items-center w-full py-16">
        <nav 
            class="flex justify-end w-full lg:w-9/12 items-center"
            v-if="!creating && !activating"
        >
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-text"
            >{{ trans('js_pages_users_users.addusers') }}</a>
        </nav>

        <users-create 
            v-if="creating"
        />

        <users-index 
            v-if="!creating && !activating"
        />
    </div>
</template>

<script>
export default {
    data() {
        return {
            creating: false,
            activating: false
        }
    },

    mounted () {
        window.events.$on('datatable:cancel', () => {
            this.creating = false
            this.activating = false
        })

        window.events.$on('user:activated', () => {
            this.activating = false
        })
        
        window.events.$on('datatable:clear', () => {
            this.creating = false
        })
    }
}
</script>