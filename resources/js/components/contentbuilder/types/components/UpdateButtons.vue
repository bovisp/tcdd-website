<template>
    <div class="flex">
        <b-button 
            type="is-info"
            size="is-small"
            @click.prevent="$emit('update')"
            v-if="showUpdateButton"
        >
            {{ trans('generic.update') }}
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
        showUpdateButton: {
            type: Boolean,
            required: false,
            default: true
        }
    }
}
</script>