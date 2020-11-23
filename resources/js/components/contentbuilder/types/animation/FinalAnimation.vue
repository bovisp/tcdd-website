<template>
    <div class="my-4">
        <p 
            class="mb-0 text-center font-light w-full text-xl"
            v-if="typeof part.data !== 'undefined' && part.data.title"
        >
            {{ part.data.title }}
        </p>

        <image-animator 
            :data="part"
            :fluid="true"
        />

        <p 
            class="mb-0 mt-2 text-gray-700 w-3/4 mx-auto"
            v-if="typeof part.data !== 'undefined' && part.data.caption"
        >
            <small>{{ part.data.caption }}</small>
        </p>
    </div>
</template>

<script>
export default {
    props: {
        part: {
            type: Object,
            required: true
        }
    }
}
</script>