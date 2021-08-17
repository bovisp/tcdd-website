<template>
    <div class="w-full mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center mb-4">
            <h2 class="text-2xl">
                {{ trans('js_pages_users_profile_components_reportingstructure.reportingstructure') }}
            </h2>

            <button 
                href="#"
                class="btn btn-text text-blue-500 no-underline"
                @click.prevent="reportingModal = true"
                v-if="hasRole(['administrator'])"
            >{{ trans('generic.edit') }}</button>
        </div>

        <div
            v-if="user.role !== 'director' && typeof user.reportingStructure !== 'undefined' && typeof user.reportingStructure['director'] !== 'undefined'"
            class="mb-4"
        >
            <reporting-structure-item
                role="director"
                :title="trans('js_pages_users_profile_components_reportingstructure.director')"
            />
        </div>

        <div
            v-if="user.role !== 'manager' && typeof user.reportingStructure !== 'undefined' && typeof user.reportingStructure['manager'] !== 'undefined'"
            class="mb-4"
        >
            <reporting-structure-item
                role="manager"
                :title="trans('js_pages_users_profile_components_reportingstructure.manager')"
            />
        </div>

        <div
            v-if="user.role !== 'employee' && typeof user.reportingStructure !== 'undefined' && typeof user.reportingStructure['employee'] !== 'undefined'"
            class="mb-4"
        >
            <reporting-structure-item
                role="employee"
                :title="trans('js_pages_users_profile_components_reportingstructure.employee')"
            />
        </div>

        <div
            v-if="user.role !== 'intern' && typeof user.reportingStructure !== 'undefined' && typeof user.reportingStructure['intern'] !== 'undefined'"
            class="mb-4"
        >
            <reporting-structure-item
                role="intern"
                :title="trans('js_pages_users_profile_components_reportingstructure.intern')"
            />
        </div>

        <div
            v-if="user.role !== 'student' && typeof user.reportingStructure !== 'undefined' && typeof user.reportingStructure['student'] !== 'undefined'"
        >
            <reporting-structure-item
                role="student"
                :title="trans('js_pages_users_profile_components_reportingstructure.student')"
            />
        </div>

        <modal 
            v-show="reportingModal"
            @close="close"
            @submit="updateReporting"
            ok-button-text="Submit"
            cancel-button-text="Cancel"
        >
            <template slot="header" v-if="role">
                {{ ucfirst(role) }} {{ trans('js_pages_users_profile_components_reportingstructure.for') }} {{ user.firstname }} {{ user.lastname }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <div class="flex justify-center w-full mb-6 pb-6 border-b border-gray-200">
                        <div>
                            <label 
                                for="roles"
                                class="block text-gray-700 font-bold mb-2"
                            >
                                {{ trans('js_pages_users_profile_components_reportingstructure.selectrole') }}
                            </label>

                            <div class="relative">
                                <select 
                                    id="roles"
                                    v-model="role"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                >
                                    <option value=""></option>

                                    <option
                                        :value="r.name"
                                        v-for="r in filteredRoles"
                                        :key="r.id"
                                        v-text="ucfirst(r.name)"
                                    ></option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <template v-if="users.length">
                        <datatable 
                            :data="users"
                            :columns="columns"
                            :selected-items="selectedUsers"
                            :per-page="10"
                            :order-keys="['lastname', 'firstname']"
                            :order-key-directions="['asc', 'asc']"
                            :has-text-filter="true"
                            :checkable="true"
                        ></datatable>
                    </template>

                    <div class="alert alert-gray mt-2" v-else>
                        {{ trans('js_pages_users_profile_components_reportingstructure.pleaseselectrole') }}
                    </div>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { filter, map } from 'lodash-es'
import ucfirst from '../../../../helpers/ucfirst'

export default {
    computed: {
        ...mapGetters({
            user: 'user/user'
        }),

        selectedUsers () {
            if (this.role === '') {
                return []
            }
            return map(this.user.reportingStructure[this.role], user => user.id)
        },

        filteredRoles () {
           return filter(this.roles, role => role.name !== 'administrator' && role.name !== this.user.role)
        }
    },

    watch: {
        role () {
            this.getUsersWithRole()
        }
    },

    data() {
        return {
            reportingModal: false,
            roles: [],
            role: '',
            users: [],
            selected: [],
            columns: [
                { field: 'firstname', title: this.trans('generic.firstname'), sortable: true },
                { field: 'lastname', title: this.trans('generic.lastname'), sortable: true },
            ],
        }
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        }),

        ucfirst,

        close () {
            this.reportingModal = false
        },

        async updateReporting () {
            let { data } = await axios.post(`${this.urlBase}/api/users/${this.user.id}/role/${this.role}`, {
                users: this.selected
            })

            await this.fetch(this.user.id)

            window.events.$emit('datatable:clear')

            this.reset()

            this.$toasted.success(data.data.message)
        },

        reset () {
            this.close()

            this.role = ''
            this.users = []
            this.selected = []
        },

        async getUsersWithRole () {
            if (this.role === '') {
                this.users = []
                return
            }

            let { data: users } = await axios.get(`${this.urlBase}/api/supervisors/${this.role}`)

            this.users = users
        }
    },
    
    async mounted () {
        let { data: roles } = await axios.get(`${this.urlBase}/api/roles`)

        this.roles = roles

        window.events.$on('users:selected', selectedUsers => {
            this.selected = selectedUsers
        })
    }
}
</script>