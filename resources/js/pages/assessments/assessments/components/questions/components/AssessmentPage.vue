<template>
    <div v-if="currentPage">
        <hr class="border my-6">

        <h2 class="text-2xl font-light">
            Page {{ currentPage.number }}
        </h2>

        <assessment-questions-content-picker
            @content:type="setType"
            v-if="!type"
        />

        <assessment-questions-content-add
            v-if="type"
            :type="type"
            @content-add:cancel="type = ''"
            :page="currentPage"
        />
    </div>
</template>

<script>
import { find } from 'lodash-es'

export default {
    props: {
        page: {
            type: Number,
            required: true
        },
        pages: {
            type: Array,
            required: true
        }
    },

    data () {
        return {
            type: '',
            currentPage: null
        }
    },

    methods: {
        setType (type) {
            this.type = type
        }
    },

    mounted () {
        this.currentPage = find(this.pages, page => page.number === this.page)

        window.events.$on('assessment-page:change', page => {
            this.currentPage = find(this.pages, p => p.number === page)
        })
    }
}
</script>