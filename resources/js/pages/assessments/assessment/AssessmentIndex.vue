<template>
    <div class="py-16">
        <h1 class="text-3xl font-bold mb-6">
            {{ assessment.name }}
        </h1>

        <p 
            class="mb-6"
            v-html="assessment.description"
        ></p>

        <div>
            <h2 class="font-light text-2xl mb-4">Exam questions by page</h2>

            <dl
                v-for="(questions, page) in pages"
                :key="page"
                class="text-normal mb-3"
            >
                <dt>
                    <strong>Page {{ page }}</strong>
                </dt>

                <template v-if="questions[0].questions.length">
                    <dd
                        v-for="question in questions[0].questions"
                        :key="question.number"
                        class="ml-2"
                    >
                        Question {{ question.number }} ({{ question.score }} points)
                    </dd>
                </template>

                <template v-else>
                    <dd class="ml-2">
                        No questions on this page.
                    </dd>
                </template>
            </dl>
        </div>

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
        },
        pages: {
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

            window.location.href = `${this.urlBase}/assessment/${this.assessment.id}/attempt/${attempt.data.id}`
        }
    }
}
</script>