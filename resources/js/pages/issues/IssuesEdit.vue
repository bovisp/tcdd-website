<template>
    <div class="w-full">
        <nav 
            class="flex justify-end w-full items-center"
            @click.prevent="cancel"
        >
            <a 
                href=""
                class="btn btn-text"
            >Cancel</a>
        </nav>

        <h1 class="text-3xl font-bold mb-4">
            Update issue
        </h1>

        <form @submit.prevent="update">
            <div class="w-full mb-4">
                <p><strong class="text-gray-700">Issued by:</strong> {{ issue.issuer }}</p>
            </div>

            <div class="w-full mb-4">
                <p><strong class="text-gray-700">Issued number:</strong> {{ issue.code }}</p>
            </div>

            <div class="w-full mb-4">
                <p><strong class="text-gray-700">Issued created:</strong> {{ issue.created_at }}</p>
            </div>

            <div
                class="w-full mb-4"
                v-if="issue.updated_at"
            >
                <p><strong class="text-gray-700">Issued updated:</strong> {{ issue.updated_at }}</p>
            </div>

            <div
                class="w-full mb-4"
                v-if="issue.closed === 'No'"
            >
                <label 
                    for="status"
                    class="block text-gray-700 font-bold mb-2"
                >
                    Status
                </label>

                <div class="relative">
                    <select 
                        id="status"
                        v-model="form.status"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.status }"
                    >
                        <option value=""></option>
                        
                        <option
                            :value="status.code"
                            v-for="status in statuses"
                            :key="status.code"
                            v-text="status.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.status"
                    v-text="errors.status[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
                v-if="issue.closed !== 'No'"
            >
                <p><strong class="text-gray-700">Issued closed:</strong> {{ issue.closed_at }}</p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="flex items-center text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.title }"
                    for="title"
                >
                    Issue 
                </label>

                <input 
                    type="text" 
                    v-model="form.title"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="title"
                    :class="{ 'border-red-500': errors.title }"
                >

                <p
                    v-if="errors.title"
                    v-text="errors.title[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="flex items-center text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.body }"
                    for="body"
                >
                    Issue description
                </label>

                <vue-editor 
                    v-model="form.body"
                ></vue-editor>

                <p
                    v-if="errors.body"
                    v-text="errors.body[0]"
                    class="text-red-500 text-sm mt-2"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Update issue
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { VueEditor, Quill } from 'vue2-editor'

export default {
    components: {
        VueEditor
    },

    data() {
        return {
            form: {
                title: '',
                body: '',
                status: ''
            },
            statuses: [
                { code: 'submitted', name: 'Submitted' },
                { code: 'in_progress', name: 'In Progress' },
                { code: 'resolved', name: 'Resolved' }
            ]      
        }
    },

    computed: {
        ...mapGetters({
            issue: 'issues/issue'
        })
    },

    methods: {
        cancel () {
            window.events.$emit('issues:edit-cancel')

            this.form.title = ''
            this.form.body = ''
            this.form.status = ''
        },

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/issues/${this.issue.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    },

    mounted () {
        this.form.title = this.issue.title
        this.form.body = this.issue.body
        this.form.status = this.issue.status
    }
}
</script>