<template>
    <div class="relative">
        <button
            class="btn relative z-20"
            @click.prevent="isOpen = !isOpen"
        >Menu&nbsp;&dtrif;</button>

        <button 
            class="fixed inset-0 h-full w-full transparent cursor-default z-10"
            v-if="isOpen"
            @click.prevent="isOpen = false"
            tabindex="-1"
        ></button>

        <div 
            class="absolute z-20 right-0 bg-white flex flex-col rounded shadow-md border border-gray-200 py-3 w-64"
            style="top: 50px;"
            v-if="isOpen"
        >
            <span class="pb-2 px-3">Logged in as: <a :href="`${urlBase}/users/${authUser.id}`"><strong>{{ authUser.name }}</strong></a></span>  

            <hr class="border-t border-gray-300">

            <a class="text-gray-900 py-2 px-3" :href="`${urlBase}/users`">Manage users</a>

            <a class="text-gray-900 py-2 px-3" :href="`${urlBase}/sections`">Manage section names</a>

            <hr class="border-t border-gray-300">

            <a 
                href="#"
                class="text-gray-900 pt-2 px-3"
                @click.prevent="logout"
            >Logout</a>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isOpen: false
        }
    },
    
    methods: {
        async logout () {
            await axios.post(`${this.urlBase}/logout`)

            window.location.href = this.urlBase
        }
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