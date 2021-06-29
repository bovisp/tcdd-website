<template>
    <div>
        <div class="mx-auto w-full lg:w-1/2 mb-4">
            <div class="flex justify-end">
                <button 
                    class="ml-auto btn btn-blue btn-sm text-sm"
                    @click.prevent="store"
                >
                    {{ trans('js_pages_assessments_assessments__components_instructors_assessmentinstructorscreate.granteditingrights') }}
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm ml-2"
                    @click.prevent="$emit('cancel')"
                >
                    {{ trans('js_pages_assessments_assessments__components_instructors_assessmentinstructorscreate.cancel') }}
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
                :has-text-filter="false"
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
                { field: 'firstname', title: this.trans('js_pages_assessments_assessments__components_instructors_assessmentinstructorscreate.firstname'), sortable: true },
                { field: 'lastname', title: this.trans('js_pages_assessments_assessments__components_instructors_assessmentinstructorscreate.lastname'), sortable: true },
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
            let { data } = await axios.post(`${this.urlBase}/api/assessments/${this.assessment.id}/instructors`, {
                users: this.selected
            })

            this.selected = []

            window.events.$emit('datatable:clear')

            this.$toasted.success(data.data.message)

            this.$emit('cancel')
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/assessments/${this.assessment.id}/instructors/create`)

        this.users = users

        window.events.$on('users:selected', users => {
            this.selected = users
        })
    }
}
</script>