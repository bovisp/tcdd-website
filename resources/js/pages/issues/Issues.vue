<template>
    <div class="flex flex-col items-center w-full py-16 mx-auto">
        <div 
            v-if="!creating && !updating"
        >
            <p class="mb-6">
                {{ trans('js_pages_issues.issuetext') }}
            </p>
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-blue"
            >{{ trans('js_pages_issues.submitissue') }}</a>
        </div>

        <issues-create 
            v-if="creating"
        />

        <issues-edit 
            v-if="updating"
        />

        <issues-index 
            v-if="!creating && !updating && hasRole(['administrator'])"
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
        window.events.$on('issues:edit', issue => {
            this.updating = true
        })

        window.events.$on('issues:edit-cancel', issue => {
            this.updating = false
        })

        window.events.$on('issues:create-cancel', issue => {
            this.creating = false
        })
    },
}
</script>