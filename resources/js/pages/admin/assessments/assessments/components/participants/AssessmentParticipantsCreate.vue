<template>
    <div>
        <div class="mx-auto w-full lg:w-1/2 mb-4">
            <div class="flex justify-end">
                <button 
                    class="ml-auto btn btn-blue btn-sm text-sm"
                    @click.prevent="store"
                >
                    Add participants
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm ml-2"
                    @click.prevent="$emit('cancel')"
                >
                    Cancel
                </button>
            </div>
        </div>

        <div class="mx-auto w-full lg:w-1/2 mb-4">
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
                { field: 'firstname', title: 'First name', sortable: true },
                { field: 'lastname', title: 'Last name', sortable: true },
            ],
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        })
    },

    methods: {
        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/assessments/${this.assessment.id}/participants`, {
                users: this.selected
            })

            this.selected = []

            window.events.$emit('datatable:clear')

            this.$toasted.success(data.data.message)

            this.$emit('cancel')
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/assessments/${this.assessment.id}/participants/create`)

        this.users = users

        window.events.$on('users:selected', users => {
            this.selected = users
        })
    }
}
</script>