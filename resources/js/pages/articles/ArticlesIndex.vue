<template>
    <ul>
        <li v-for="article in articles">
            {{ article.title }}
        </li>
    </ul>
</template>

<script>
import {mapActions, mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            articles: 'articles/articles'
        })
    }
}
</script>