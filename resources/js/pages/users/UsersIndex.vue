<template>
    <div class="w-full lg:w-9/12">
        <h1 class="text-3xl font-bold mb-4">
            Users
        </h1> 

        <datatable 
            :data="users"
            :columns="columns"
            :per-page="10"
            :order-keys="['section']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-action="true"
            action-text="Profile"
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
                { field: 'firstname', title: 'First name', sortable: true },
                { field: 'lastname', title: 'Last name', sortable: true },
                { field: 'section', title: 'Section', sortable: false },
                { field: 'role', title: 'Role', sortable: false }
            ]
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/users`)

        this.users = users.data
    }
}
</script>