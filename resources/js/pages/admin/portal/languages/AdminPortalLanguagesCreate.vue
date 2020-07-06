<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            New language
        </h1>

        <form 
            @submit.prevent="store"
        >
            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.language_en }"
                    for="language_en"
                >
                    Language (English)
                </label>

                <input 
                    type="text" 
                    v-model="form.language_en"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="language_en"
                    :class="{ 'border-red-500': errors.language_en }"
                >

                <p
                    v-if="errors.language_en"
                    v-text="errors.language_en[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.language_fr }"
                    for="language_fr"
                >
                    Language (French)
                </label>

                <input 
                    type="text" 
                    v-model="form.language_fr"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="language_fr"
                    :class="{ 'border-red-500': errors.language_fr }"
                >

                <p
                    v-if="errors.language_fr"
                    v-text="errors.language_fr[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Add language
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

export default {
    data() {
        return {
            form: {
                language_en: '',
                language_fr: ''
            }
        }
    },

    methods: {
        cancel () {
            window.events.$emit('portal-languages:create-cancel')

            this.form.language_en = ''
            this.form.language_fr = ''
        },

        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/admin/portal/languages`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    }
}
</script>