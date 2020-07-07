<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Training Portal courses
        </h1> 

        <datatable 
            :data="courses"
            :columns="columns"
            :per-page="10"
            :order-keys="['categoryName', 'name', 'languageName']"
            :order-key-directions="['asc', 'asc', 'asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="Edit"
            event="portal-courses:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'categoryName', title: 'Category', sortable: true },
                { field: 'name', title: 'Name', sortable: true },
                { field: 'languageName', title: 'Language', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            courses: 'portalCourses/courses'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'portalCourses/fetch',
            setEdit: 'portalCourses/setEdit'
        })
    },
    
    async mounted () {
        await this.fetch()

        window.events.$on('portal-courses:edit', course => {
            this.setEdit(course)
        })
    }
}
</script>