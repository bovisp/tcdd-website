<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_admin_portal_courses_adminportalcoursesindex.portalcourses') }}
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
                { field: 'categoryName', title: trans('generic.category'), sortable: true },
                { field: 'name', title: trans('generic.name'), sortable: true },
                { field: 'languageName', title: trans('generic.language'), sortable: true }
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