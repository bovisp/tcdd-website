<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Training Portal languages
        </h1> 

        <datatable 
            :data="languages"
            :columns="columns"
            :per-page="10"
            :order-keys="['language']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="portal-languages:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'language', title: 'Language', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            languages: 'portalLanguages/languages'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'portalLanguages/fetch',
            setEdit: 'portalLanguages/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('portal-languages:edit', language => {
            this.setEdit(language)
        })
    }
}
</script>