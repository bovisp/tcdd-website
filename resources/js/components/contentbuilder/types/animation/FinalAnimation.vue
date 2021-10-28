<template>
    <div class="my-4">
        <p 
            class="mb-4 text-center font-light w-full text-xl"
            v-if="typeof data.data !== 'undefined' && data.data.title"
        >
            {{ data.data.title }}
        </p>

        <div v-if="data.data">
            <image-animator 
                :files="data.data.images"
                :fluid="true"
            />
        </div>

        <p 
            class="mb-0 mt-2 text-gray-700 w-3/4 mx-auto"
            v-if="typeof data.data !== 'undefined' && data.data.caption"
        >
            <small>{{ data.data.caption }}</small>
        </p>
    </div>
</template>

<script>
import contentBuilderData from '../../../../mixins/contentBuilder'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        data: {
            type: Object,
            required: false
        }
    },
}
</script>