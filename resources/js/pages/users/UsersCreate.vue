<template>
    <div class="w-full lg:w-9/12">   
        <h1 class="text-3xl font-bold mb-4">
            {{ trans('js_pages_users_userscreate.addusers') }}
        </h1> 

        <div class="flex justify-around w-full mb-6 pb-6 border-b border-gray-200">
            <div class="w-full md:w-1/2 lg:w-1/4">
                <label 
                    for="roles"
                    class="block text-gray-700 font-bold mb-2"
                >
                    {{ trans('js_pages_users_userscreate.registeruseras') }}...
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
                            v-for="r in roles"
                            :key="r.id"
                            v-text="ucfirst(r.name)"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div  class="w-full md:w-1/2 lg:w-1/4">
                <label 
                    for="roles"
                    class="block text-gray-700 font-bold mb-2"
                >
                    {{ trans('js_pages_users_userscreate.putintosection') }}...
                </label>

                <div class="relative">
                    <select 
                        id="sections"
                        v-model="section"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    >
                        <option value=""></option>

                        <option
                            :value="s.id"
                            v-for="s in sections"
                            :key="s.id"
                            v-text="s.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <datatable 
            :data="users"
            :columns="columns"
            :per-page="10"
            :order-keys="['lastname', 'firstname']"
            :order-key-directions="['asc', 'asc']"
            :has-text-filter="true"
            :checkable="true"
        >
            <button
                @click.prevent="cancel"
                class="btn btn-text text-red-500 mr-2"
            >{{ trans('js_pages_users_userscreate.cancel') }}</button>

            <button 
                @click.prevent="store"
                class="btn btn-blue"
            >{{ trans('js_pages_users_userscreate.submit') }}</button>
        </datatable>
    </div>
</template>

<script>
import ucfirst from '../../helpers/ucfirst'
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            users: [],
            columns: [
                { field: 'firstname', title: this.trans('js_pages_users_userscreate.firstname'), sortable: true },
                { field: 'lastname', title: this.trans('js_pages_users_userscreate.lastname'), sortable: true },
                { field: 'email', title: this.trans('js_pages_users_userscreate.email'), sortable: false }
            ],
            selected: [],
            roles: [],
            section: '',
            role: ''
        }
    },

    computed: {
        ...mapGetters({
            sections: 'sections/sections'
        })
    },

    methods: {
        ...mapActions({
            fetchSections: 'sections/fetch'
        }),

        ucfirst, 
        
        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/users/moodle`, {
                role: this.role,
                section: this.section,
                users: this.selected
            })

            this.reset()

            window.events.$emit('datatable:clear')

            this.$toasted.success(data.data.message)
        },

        reset () {
            this.selected = []
            this.roles = []
            this.role = ''
        },

        cancel () {
            this.reset()

            window.events.$emit('datatable:cancel')
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/users/moodle/create`)
        let { data: roles } = await axios.get(`${this.urlBase}/api/roles`)

        await this.fetchSections()

        this.users = users
        this.roles = roles

        window.events.$on('users:selected', users => {
            this.selected = users
        })
    }
}
</script>