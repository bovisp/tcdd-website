<template>
    <div>
        <div class="flex mb-4">
            <button 
                class="ml-auto btn btn-blue btn-sm text-sm"
                @click.prevent="update"
                v-if="typeof permission.users !== 'undefined' && permission.users.length"
            >
                {{ trans('generic.update') }}
            </button>

            <button 
                class="btn btn-text btn-sm text-sm"
                :class="{ 
                    'ml-2' : typeof permission.users !== 'undefined' && permission.users.length,
                    'ml-auto' : typeof permission.users === 'undefined'
                }"
                @click.prevent="$emit('create')"
            >
                {{ trans('js_pages_permissions_components_userspermissionsindex.giveotheruserspermission') }}
            </button>
        </div>

        <datatable 
            v-if="typeof permission.users !== 'undefined' && permission.users.length"
            :data="permission.users"
            :columns="columns"
            :selected-items="selectedUsers"
            :per-page="10"
            :order-keys="['lastname', 'firstname']"
            :order-key-directions="['asc', 'asc']"
            :has-text-filter="false"
            :checkable="true"
        ></datatable>

        <div 
            class="alert alert-blue"
            v-else
        >
            {{ trans('js_pages_permissions_components_userspermissionsindex.nousers') }}
        </div>
    </div>
</template>

<script>
import { map } from 'lodash-es'
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            selected: [],
            columns: [
                { field: 'firstname', title: this.trans('generic.firstname'), sortable: true },
                { field: 'lastname', title: this.trans('generic.lastname'), sortable: true },
            ],
        }
    },

    computed: {
        ...mapGetters({
            permissions: 'permissions/permissions',
            permission: 'permissions/permission'
        }),

        selectedUsers () {
            return map(this.permission.users, user => user.id)
        }
    },

    watch: {
        permission: {
            deep: true,

            handler () {
                this.selected = this.selectedUsers

                window.events.$emit('datatable:reload-selected', this.selected)
            }
        }
    },

    methods: {
        ...mapActions({
            fetchPermissions: 'permissions/fetch'
        }),

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/permissions/${this.permission.id}/users`, {
                users: this.selected
            })

            await this.reload()

            this.$toasted.success(data.data.message)
        },

        async reload () {
            await this.fetchPermissions()
        }
    },

    async mounted () {
        this.selected = this.selectedUsers

        window.events.$on('users:selected', selectedUsers => {
            this.selected = selectedUsers
        })

        window.events.$on('permissions:reload', async () => {
            await this.reload()
        })
    }
}
</script>