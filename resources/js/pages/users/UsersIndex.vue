<template>
    <div class="w-full lg:w-9/12">
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_users_usersindex.users') }}
        </h1> 

        <datatable 
            :data="users"
            :columns="columns"
            :per-page="10"
            :order-keys="['section']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-action="true"
            :action-text="trans('js_pages_users_usersindex.profile')"
            :action-link="`${urlBase}/users`"
            action-id="id"
        />
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            columns: [
                { field: 'firstname', title: this.trans('js_pages_users_usersindex.firstname'), sortable: true },
                { field: 'lastname', title: this.trans('js_pages_users_usersindex.lastname'), sortable: true },
                { field: 'section', title: this.trans('js_pages_users_usersindex.section'), sortable: false },
                { field: 'role', title: this.trans('js_pages_users_usersindex.role'), sortable: false }
            ]
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/users`)

        this.users = users.data
    }
}
</script>