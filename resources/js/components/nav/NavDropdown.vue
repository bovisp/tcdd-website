<template>
    <div class="relative">
        <button
            class="btn relative z-20"
            @click.prevent="isOpen = !isOpen"
        >{{ trans('usermenu.menu') }}&nbsp;&dtrif;</button>

        <button 
            class="fixed inset-0 h-full w-full transparent cursor-default z-10"
            v-if="isOpen"
            @click.prevent="isOpen = false"
            tabindex="-1"
        ></button>

        <div 
            class="absolute z-20 right-0 bg-white flex flex-col rounded shadow-md border border-gray-200 py-1 w-64"
            v-if="isOpen"
        >
            <span class="pb-2 px-3">{{ trans('usermenu.loggedInAs') }}: <strong>{{ me.fullname }}</strong></span>  

            <hr class="border-t border-gray-300 my-0">

            <a 
                :href="`${urlBase}`"
                class="text-gray-900 py-2 px-3"
            >{{ trans('generic.homepage') }}</a>

            <template v-if="hasRole(['administrator', 'director', 'manager', 'employee'])">
                <a class="text-gray-900 py-2 px-3" :href="`${urlBase}/admin`">{{ trans('usermenu.adminSection') }}</a>
            </template>

            <a class="text-gray-900 py-2 px-3" :href="`${urlBase}/issues`">{{ trans('usermenu.submitAnIssue') }}</a>

            <hr class="border-t border-gray-300 my-0">

            <a 
                href="#"
                class="text-gray-900 pt-2 px-3"
                @click.prevent="logout"
            >{{ trans('usermenu.logout') }}</a>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            isOpen: false
        }
    },

    computed: {
        ...mapGetters({
            me: 'user/me'
        })
    },
    
    methods: {
        ...mapActions({
            fetchMe: 'user/me'
        }),

        async logout () {
            await axios.post(`${this.urlBase}/logout`)

            window.location.href = this.urlBase
        }
    },

    async mounted () {
        await this.fetchMe()
    },

    created() {
        const handleEscape = e => {
            if (e.key === 'Esc' || e.key === 'Escape') {
                this.isOpen = false
            }
        }

        document.addEventListener('keydown', handleEscape)

        this.$once('hook:beforeDestroy', () => {
            document.removeEventListener('keydown', handleEscape)
        })
    },
}
</script>