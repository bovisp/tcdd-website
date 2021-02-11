<template>
    <div
        class="fixed h-16 w-full bottom-0 left-0 mx-auto flex items-center px-8 bg-gray-100"
        style="box-shadow: 0px 0 10px rgba(0, 0, 0, 0.1);"
        v-if="attempt"
    >
        <span
            class="text-lg"
            :class="{ 'text-red-700': warningClass }"
            v-if="attempt.time_remaining"
        >
            <strong>Time remaining:</strong> {{ attempt.time_remaining }} minutes
        </span>

        <template v-if="attempt.pages">
            <button 
                class="btn btn-blue ml-auto"
                :class="hasPrevPage ? '' : 'btn-disabled'"
                :disabled="!hasPrevPage"
                @click.prevent="changePage(currentPage.number - 1)"
            >
                <i class="fas fa-chevron-left"></i>

                Save all and previous page
            </button>

            <button 
                class="btn btn-blue ml-4"
                :class="hasNextPage ? '' : 'btn-disabled'"
                :disabled="!hasNextPage"
                @click.prevent="changePage(currentPage.number + 1)"
            >
                Save all and next page

                <i class="fas fa-chevron-right"></i>
            </button>

            <button 
                class="btn btn-green ml-4"
                @click.prevent="review"
            >
                Review all and submit
            </button>
        </template>

        <modal 
            v-show="modalActive"
            @submit="warningConfirmed"
            :has-cancel-button="false"
            ok-button-text="OK"
        >
            <template slot="header">
                Two minute warning
            </template>

            <template slot="body">
                <div class="my-4">
                    You have less than two minutes left to complete the assessment. 
                    Please check your work and submit. If the timer reaches 0 minutes, 
                    your work will automatically be saved and you will be locked 
                    out of this exam.
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            warningClass: false,
            modalActive: false,
            hasConfirmed: false
        }
    },

    computed: {
        ...mapGetters({
            attempt: 'assessment/attempt',
            currentPage: 'assessment/currentPage'
        }),

        hasPrevPage () {
            if (this.attempt.pages.length === 1 || this.currentPage.number === 1) {
                return false
            }

            return true
        },

        hasNextPage () {
            if (this.attempt.pages.length === 1 || this.currentPage.number === this.attempt.pages.length) {
                return false
            }

            return true
        }
    },

    methods: {
        ...mapActions({
            changePage: 'assessment/changePage',
            setReviewStatus: 'assessment/setReviewStatus'
        }),

        review () {
            this.setReviewStatus(true)
        },

        warningConfirmed () {
            this.modalActive = false

            if (!this.hasConfirmed) {
                this.hasConfirmed = true
            }
        }
    },

    mounted () {
        setInterval(() => {
            if (this.attempt.time_remaining <= 5) {
                this.warningClass = true
            }

            if (this.attempt.time_remaining === 2) {
                if (!this.hasConfirmed) {
                    this.modalActive = true
                }
            }
        }, 5000)

        window.events.$on('assessment:five-min-warning', () => this.warningClass = true)
    }
}
</script>