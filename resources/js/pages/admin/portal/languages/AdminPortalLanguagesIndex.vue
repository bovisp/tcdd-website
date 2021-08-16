<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_admin_portal_languages_adminportallanguagesindex.portallanguages') }}
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
                { field: 'language', title: trans('generic.language'), sortable: true }
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