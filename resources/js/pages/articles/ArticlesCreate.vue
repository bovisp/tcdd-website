<template>
    <div>
        <form>
            <b-field>
                <b-input 
                    placeholder="Add your title..."
                    size="is-large"
                    class="borderless-input borderless-input-lg"
                    v-model="form.title"
                ></b-input>
            </b-field>
        </form>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data () {
        return {
            form: {
                title: ''
            }
        }
    },

    methods: {
        ...mapActions({
            createTempArticle: 'articles/createTempArticle'
        })
    },
    
    async mounted () {
        await this.createTempArticle();

        window.events.$on('article:create-cancel', () => {
            this.form.title = ''
        })
    }
}
</script>