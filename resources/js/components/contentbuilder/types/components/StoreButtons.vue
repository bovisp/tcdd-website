<template>
    <div class="flex">
        <b-button 
            type="is-info"
            size="is-small"
            @click.prevent="$emit('store')"
            v-if="showCreateButton"
        >
            {{ trans('generic.create') }}
        </b-button>
        
        <b-button 
            class="ml-auto"
            type="is-text"
            size="is-small"
            @click.prevent="$emit('cancel')"
        >
            {{ trans('generic.cancel') }}
        </b-button>
    </div>
</template>

<script>
export default {
    props: {
        showCreateButton: {
            type: Boolean,
            required: false,
            default: true
        }
    }
}
</script>