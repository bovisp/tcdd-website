<template>
    <div class="flex">
        <b-button 
            type="is-info"
            size="is-small"
            @click.prevent="$emit('store')"
        >
            Create
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