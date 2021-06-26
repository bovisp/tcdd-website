<template>
    <div>
        <div class="mx-auto w-full lg:w-1/2 mb-4">
            <div class="flex justify-end">
                <button 
                    class="ml-auto btn btn-blue btn-sm text-sm"
                    @click.prevent="update"
                    v-if="typeof assessment.editors !== 'undefined' && assessment.editors.length"
                >
                    Update instructors
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm"
                    :class="{ 
                        'ml-2' : typeof assessment.editors !== 'undefined' && assessment.editors.length,
                        'ml-auto' : typeof assessment.editors === 'undefined'
                    }"
                    @click.prevent="$emit('create')"
                >
                    Add more instructors
                </button>
            </div>
        </div>

        <div class="flex justify-center">
            <div 
                class="w-full lg:w-1/2"
            >
                <datatable 
                    v-if="typeof assessment.editors !== 'undefined' && assessment.editors.length"
                    :data="assessment.editors"
                    :columns="columns"
                    :selected-items="selectedUsers"
                    :per-page="10"
                    :order-keys="['lastname', 'firstname']"
                    :order-key-directions="['asc', 'asc']"
                    :has-text-filter="false"
                    :checkable="true"
                    key="instructors"
                ></datatable>

                <div 
                    class="alert alert-blue w-full lg:w-1/2"
                    v-else
                >
                    There are currently no users who can edit this assessment.
                </div>
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
            return map(this.assessment.editors, user => user.id)
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
            fetchAssessment: 'assessments/fetchAssessment'
        }),

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/assessments/${this.assessment.id}/instructors`, {
                users: this.selected
            })

            await this.reload()

            this.$toasted.success(data.data.message)
        },

        async reload () {
            await this.fetchAssessment(this.assessment.id)

            window.events.$emit('datatable:reload-selected', map(
                this.assessment.editors, editor => editor.id
            ))
        }
    },

    async mounted () {
        await this.reload()

        this.selected = this.selectedUsers

        window.events.$on('users:selected', async selectedUsers => {
            if (selectedUsers.length === 0) {
                await this.reload()

                window.events.$emit('datatable:reload-selected', map(
                    this.assessment.editors, editor => editor.id
                ))

                return
            }

            this.selected = selectedUsers
        })

        window.events.$on('assessments:reload', async () => {
            await this.reload()
        })
    }
}
</script>