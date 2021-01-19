<template>
    <div class="py-16">
        <h1 class="text-3xl font-bold mb-6">
            {{ assessment.name }}
        </h1>

        <p class="mb-6">
            {{ assessment.description }}
        </p>

        <div class="flex justify-end">
            <button 
                class="btn btn-blue"
                @click.prevent="modalActive = true"
            >
                Start assessment
            </button>
        </div>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="confirm"
        >
            <template slot="header">
                Begin: {{ assessment.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p>
                        Are you sure you want to start this exam?
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
export default {
    props: {
        assessment: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            modalActive: false
        }
    },

    methods: {
        close () {
            this.modalActive = false
        },

        confirm () {
            this.modalActive = false
        }
    }
}
</script>