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

            <template v-if="!isEmpty(article)">
                <b-field>
                    <content-builder 
                        :id="article.contentBuilder.en"
                    />
                </b-field>
                
                <b-field>
                    <content-builder 
                        :id="article.contentBuilder.fr"
                    />
                </b-field>
            </template>
        </form>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { isEmpty } from 'lodash-es'

export default {
    data () {
        return {
            form: {
                title: ''
            }
        }
    },

    computed: {
        ...mapGetters({
            article: 'articles/article'
        })
    },

    methods: {
        isEmpty,

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