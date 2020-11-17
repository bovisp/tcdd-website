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
            New Issue
        </h1> 

        <form @submit.prevent="store">
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
                    Please describe this issue
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
                    Submit new issue
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
                body: ''
            } 
        }
    },

    methods: {
        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/issues`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
        
        cancel () {
            window.events.$emit('issues:create-cancel')

            this.form.title = ''
            this.form.body = ''
        }
    }
}
</script>