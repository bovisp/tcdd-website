<template>
    <div>
        <h2 class="text-xl">
            {{ title }}
        </h2>

        <ul v-if="users && users.length">
            <li
                v-for="u in users"
                :key="u.id"
                class="pl-2"
            >
                <template v-if="hasRole(['administrator']) || parseInt(authUser.rank) < u.rank">
                    <a :href="`${u.id}`">
                        {{ u.firstname }} {{ u.lastname }}
                    </a>
                </template>
                
                <span v-else>
                    {{ u.firstname }} {{ u.lastname }}
                </span>
            </li>
        </ul>

        <div class="alert alert-gray mt-2" v-else>
            No {{ pluralize(title) }} have been linked to this user.
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import pluralize from 'pluralize'

export default {
    props: {
        role: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: true
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user'
        }),

        users () {
            if (this.user && this.user.reportingStructure) {
                return this.user.reportingStructure[this.role]
            }
        }
    },

    methods: {
        pluralize
    }
}
</script>