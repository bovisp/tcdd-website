<template>
    <div 
        class="w-full mb-6"
        v-if="(parseInt(user.id) === parseInt(authUser.id)) || hasRole['administrator']"
    >
        <button
            v-if="!changing"
            @click.prevent="changing = true"
            class="btn btn-text text-sm"
        >Change password</button>

        <template v-else>
            <div
                class="w-full lg:w-1/2"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.password }"
                    for="password"
                >
                    New password
                </label>

                <input 
                    type="password" 
                    v-model="form.password"
                    class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="password"
                >

                <p
                    v-if="errors.password"
                    v-text="errors.password[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full lg:w-1/2 mt-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.passwordConfirm }"
                    for="passwordConfirm"
                >
                    Confirm password
                </label>

                <input 
                    type="password" 
                    v-model="form.passwordConfirm"
                    class="shadow appearance-none border rounded w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="passwordConfirm"
                >

                <p
                    v-if="errors.passwordConfirm"
                    v-text="errors.passwordConfirm[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>
            
            <div
                class="w-full lg:w-1/2 mt-4"
            >
                <button 
                    class="btn btn-blue text-sm"
                    @click.prevent="update"
                >
                    Change password
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </template>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            changing: false,
            form: {
                password: '',
                passwordConfirm: ''
            }
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        }),

        async update () {
            let { data } = await axios.patch(`${this.urlBase}/api/users/${this.user.id}/password`, this.form)

            await this.fetch(this.user.id)

            this.cancel()

            this.$toasted.success(data.data.message)
        },

        cancel () {
            this.changing = false
            this.form.password = ''
            this.form.passwordConfirm = ''
        }
    }
}
</script>