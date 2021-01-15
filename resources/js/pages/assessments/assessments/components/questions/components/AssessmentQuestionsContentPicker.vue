<template>
    <div class="flex justify-end">
        <button 
            class="btn btn-sm btn-text text-sm text-blue-500"
            v-if="!adding"
            @click.prevent="add"
        >
            <i class="fas fa-plus mr-1"></i>
            Add content
        </button>

        <div
            class="w-full lg:w-1/3"
            v-else
        >
            <label 
                for="content_type"
                class="block text-gray-700 font-bold mb-2"
            >
                Choose a type...
            </label>

            <div class="relative">
                <select 
                    id="content_type"
                    v-model="contentType"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                >
                    <option value=""></option>

                    <option
                        :value="type.code"
                        v-for="type in types"
                        :key="type.code"
                        v-text="type.name"
                    ></option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            adding: false,
            contentType: '',
            types: [
                { code: 'question', name: 'Question' },
                { code: 'content', name: 'Explanitory content' },
            ]
        }
    },

    watch: {
        contentType () {
            this.$emit('content:type', this.contentType)
        }
    },

    methods: {
        add () {
            this.adding = true
        }
    }
}
</script>