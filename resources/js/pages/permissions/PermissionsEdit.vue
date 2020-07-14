<template>
    <div class="w-full">
        <div class="flex w-full mb-6">
            <button 
                class="btn btn-text text-blue-500 ml-auto"
                @click.prevent="cancel"
            >
                Back to permissions
            </button>
        </div>

        <h1 class="text-3xl font-bold mb-4">
            Edit: Permission - {{ permission.name }}
        </h1>

        <users-permissions-index 
            v-if="showUsersPermissionsIndex"
            @create="showUsersPermissionsIndex = false"
        />

        <users-permissions-create 
            v-else
            @cancel="reload"
        />

        <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

        <destroy-permission 
            v-if="hasRole(['administrator'])"
            @close="cancel"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data () {
        return {
            showUsersPermissionsIndex: true
        }
    },

    computed: {
        ...mapGetters({
            permission: 'permissions/permission'
        })
    },
    
    methods: {
        cancel () {
            window.events.$emit('permissions:edit-cancel')
        },

        reload () {
            this.showUsersPermissionsIndex = true

            window.events.$emit('permissions:reload')
        },

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/permissions/${this.permission.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    }
}
</script>