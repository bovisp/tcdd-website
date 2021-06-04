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
            >{{ trans('js_pages_admin_sections_sections.addsections') }}</a>
        </nav>

        <sections-create 
            v-if="creating"
        />

        <sections-edit 
            v-if="updating"
        />

        <sections-index 
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
        window.events.$on('sections:edit', () => {
            this.updating = true
        })
        window.events.$on('sections:edit-cancel', () => {
            this.updating = false
        })
        window.events.$on('sections:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>