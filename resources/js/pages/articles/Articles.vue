<template>
    <section class="section">
        <div class="columns is-centered">
            <div class="column is-two-thirds-desktop">
                <div class="level">
                    <div class="level-left"></div>

                    <div class="level-right">
                        <div class="level-item">
                            <b-button
                                type="is-info"
                                @click.prevent="creating = true"
                                v-if="!creating"
                            >
                                New article
                            </b-button>
                            
                            <b-button
                                type="is-text"
                                class="has-text-danger"
                                @click.prevent="remove"
                                v-if="creating"
                            >
                                Cancel
                            </b-button>
                        </div>
                    </div>
                </div>

                <articles-index 
                    v-if="!creating"
                />

                <articles-create 
                    v-if="creating"
                />
            </div>
        </div>
    </section>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data () {
        return {
            creating: false
        }
    },

    computed: {
        ...mapGetters({
            article: 'articles/article'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'articles/fetch',
            destroy: 'articles/destroy'
        }),

        async remove () {
            await this.destroy()

            this.cancel()
        },

        cancel () {
            window.events.$emit('article:create-cancel')

            this.creating = false
        },
    },

    async mounted () {
        await this.fetch()
    }
}
</script>