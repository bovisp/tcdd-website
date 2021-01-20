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
import { mapActions } from 'vuex'

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
        ...mapActions ({
            start: 'assessment/start'
        }),

        close () {
            this.modalActive = false
        },

        async confirm () {
            this.modalActive = false

            let { data: attempt } = await this.start(this.assessment.id)

            window.location.href = `${this.urlBase}/assessment/${this.assessment.id}/attempt/${attempt.id}`
        }
    }
}
</script>