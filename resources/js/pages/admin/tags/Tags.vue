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
            >{{ trans('js_pages_admin_tags_tags.addtag') }}</a>
        </nav>

        <tags-create 
            v-if="creating"
        />

        <tags-edit 
            v-if="updating"
        />

        <tags-index 
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
        window.events.$on('tags:edit', () => {
            this.updating = true
        })
        window.events.$on('tags:edit-cancel', () => {
            this.updating = false
        })
        window.events.$on('tags:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>