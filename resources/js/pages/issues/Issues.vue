<template>
    <div class="flex flex-col items-center w-full py-16 mx-auto">
        <div 
            v-if="!creating && !updating"
        >
            <p class="mb-6">
                Are you experiencing an issue with the application? Is so, please click the "Submit issue" button below. 
                Please make sure to provide as much detail as possible so that the issue can be fixed as quickly as possible. 
                You will receive an email confirmation when you submit the issue, when the issue is being fixed and when the issue has been resolved. 
                We apologize for the inconvenience this issue has caused you and your team. We hope to resolve every issue within 1-3 business days.
            </p>
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-blue"
            >Submit issue</a>
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