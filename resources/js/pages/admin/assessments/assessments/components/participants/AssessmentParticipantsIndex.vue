<template>
    <div>
        <div class="mx-auto w-full lg:w-1/2 mb-4">
            <div class="flex justify-end">
                <button 
                    class="btn btn-blue btn-sm text-sm"
                    @click.prevent="update"
                    v-if="typeof assessment.participants !== 'undefined' && assessment.participants.length"
                >
                    Update
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm"
                    :class="{ 
                        'ml-2' : typeof assessment.participants !== 'undefined' && assessment.participants.length,
                        'ml-auto' : typeof assessment.participants === 'undefined'
                    }"
                    @click.prevent="$emit('create')"
                >
                    Add more participants
                </button>
            </div>
        </div>

        <div class="flex justify-center">
            <div 
                class="w-full lg:w-1/2"
                v-if="typeof assessment.participants !== 'undefined' && assessment.participants.length"
            >
                <datatable 
                    :data="assessment.participants"
                    :columns="columns"
                    :selected-items="selectedUsers"
                    :per-page="10"
                    :order-keys="['lastname', 'firstname']"
                    :order-key-directions="['asc', 'asc']"
                    :has-text-filter="false"
                    :checkable="true"
                ></datatable>
            </div>

            <div 
                class="alert alert-blue w-full lg:w-1/2"
                v-else
            >
                There are currently no users who can participate in this assessment.
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { map } from 'lodash-es'

export default {
    data () {
        return {
            selected: [],
            columns: [
                { field: 'firstname', title: 'First name', sortable: true },
                { field: 'lastname', title: 'Last name', sortable: true },
            ]
        }
    },

    computed: {
        ...mapGetters({
            assessment: 'assessments/assessment'
        }),

        selectedUsers () {
            return map(this.assessment.participants, user => user.id)
        }
    },

    watch: {
        assessment: {
            deep: true,

            handler () {
                this.selected = this.selectedUsers

                window.events.$emit('datatable:reload-selected', this.selected)
            }
        }
    },

    methods: {
        ...mapActions({
            fetchAssessments: 'assessments/fetch'
        }),

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/assessments/${this.assessment.id}/participants`, {
                users: this.selected
            })

            await this.reload()

            this.$toasted.success(data.data.message)
        },

        async reload () {
            await this.fetchAssessments()
        }
    },

    async mounted () {
        this.selected = this.selectedUsers

        window.events.$on('users:selected', selectedUsers => {
            this.selected = selectedUsers
        })

        window.events.$on('assessments:reload', async () => {
            await this.reload()
        })
    }
}
</script>