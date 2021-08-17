<template>
    <div>
        <div class="flex mb-4">
            <button 
                class="ml-auto btn btn-blue btn-sm text-sm"
                @click.prevent="store"
            >
                {{ trans('js_pages_permissions_components_userspermissioncreate.grantpermission') }}
            </button>

            <button 
                class="btn btn-text btn-sm text-sm ml-2"
                @click.prevent="$emit('cancel')"
            >
                {{ trans('generic.cancel') }}
            </button>
        </div>

        <datatable 
            :data="users"
            :columns="columns"
            :per-page="10"
            :order-keys="['lastname', 'firstname']"
            :order-key-directions="['asc', 'asc']"
            :has-text-filter="true"
            :checkable="true"
        ></datatable>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data () {
        return {
            users: [],
            selected: [],
            columns: [
                { field: 'firstname', title: this.trans('generic.firstname'), sortable: true },
                { field: 'lastname', title: this.trans('generic.lastname'), sortable: true },
            ],
        }
    },

    computed: {
        ...mapGetters({
            permission: 'permissions/permission'
        })
    },

    methods: {
        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/permissions/${this.permission.id}/users`, {
                users: this.selected
            })

            this.selected = []

            window.events.$emit('datatable:clear')

            this.$toasted.success(data.data.message)

            this.$emit('cancel')
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/permissions/${this.permission.id}/users/create`)

        this.users = users

        window.events.$on('users:selected', users => {
            this.selected = users
        })
    }
}
</script>