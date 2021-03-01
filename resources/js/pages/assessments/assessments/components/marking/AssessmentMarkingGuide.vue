<template>
    <div 
        class="my-4"
        v-if="guide"
    >
        <button 
            class="btn btn-sm text-sm btn-text"
            @click.prevent="showMarkingGuide = !showMarkingGuide"
        >
            <i 
                class="fas mr-1"
                :class="showMarkingGuide ? 'fa-minus' : 'fa-plus'"
            ></i>
            {{ showMarkingGuide ? 'Hide' : 'Show' }} marking guide
        </button>

        <div 
            class="my-4"
            v-html="guide"
            v-if="showMarkingGuide"
        ></div>
    </div>
</template>

<script>
export default {
    props: {
        guide: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            showMarkingGuide: false
        }
    }
}
</script>