<template>
    <div 
        v-show="isActive"
        class="p-6 border border-gray-300 rounded z-0 rounded-tl-none"
    >
        <slot></slot>
    </div>  
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            required: true 
        },
        selected: {
            default: false 
        }
    },

    data() {
        return {
            isActive: false
        };
    },

    watch: {
        isActive () {
            if (this.isActive) {
                this.$emit('tabs:isactive')
            }
        }
    },
    
    computed: {
        href() {
            return '#' + this.name.toLowerCase().replace(/ /g, '-');
        }
    },
    
    mounted() {
        this.isActive = this.selected;
    },
}
</script>