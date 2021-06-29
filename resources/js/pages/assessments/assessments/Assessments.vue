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
            >{{ trans('js_pages_assessments_assessments_assessments.createassessment') }}</a>
        </nav>

        <assessments-create 
            v-if="creating"
        />

        <assessments-edit 
            v-if="updating"
        />

        <assessments-index 
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
        window.events.$on('assessments:edit', () => {
            this.updating = true
        })

        window.events.$on('assessments:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('assessments:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>