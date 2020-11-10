<template>
    <p v-if="typeof contentIds !== 'undefined' && contentIds !== null">
        {{ contentIds[lang] }}
    </p>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        lang: {
            type: String,
            required: true
        }
    },

    computed: {
        ...mapGetters({
            questionId: 'questions/tempId',
            contentIds: 'questions/contentIds'
        })
    },

    watch: {
        async contentIds () {
            let { data } = await axios.get(`/api/content-builder/${this.contentIds[this.lang]}`)

            console.log(data)
        }
    }
}
</script>