<template>
     <div>
         <nav class="bg-white">
            <div class="flex -mb-px z-10">
                <a v-for="(tab, index) in tabs"
                   class="no-underline border rounded rounded-br-none rounded-bl-none tracking-wide p-3"
                   :class="{ 'bg-gray-100': tab.isActive, 'border-r-0': index !== tabs.length - 1 }"
                   :style="{ borderBottomColor: tab.isActive ? '#f7fafc': '' }"
                   :href="tab.href" 
                   @click="selectTab(tab)"
                   :key="tab.name"
                >
                    {{ tab.name }}
                </a>
            </div>
        </nav>
        
        <div class="tabs-details">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return { 
            tabs: [] 
        };
    },

    created() {
        this.tabs = this.$children;
    },
    
    methods: {
        selectTab(selectedTab) {
            this.tabs.forEach(tab => {
                tab.isActive = (tab.href == selectedTab.href);
            });
        }
    }
}
</script>