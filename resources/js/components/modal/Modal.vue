<template>
    <div>
        <overlay 
            @close="close"
        />

        <div 
            class="absolute z-20 p-5 bg-white rounded shadow-xl w-10/12 md:w-8/12 lg:w-6/12 xl:w-4/12"
            style="left: 50%; top: 50%; transform: translate(-50%, -50%);"
        >
            <h1
                v-if="this.$slots.header"
                class="text-2xl"
            >
                <slot name="header"></slot>
            </h1>

            <div
                v-if="this.$slots.body"
            >
                <slot name="body"></slot>
            </div>

            <div class="flex w-full">
                <div 
                    class="w-1/2 pr-2 ml-auto"
                    v-if="hasCancelButton"
                >
                    <button 
                        class="btn btn-outline w-full"
                        @click.prevent="$emit('close')"
                    >
                        {{ cancelButtonText }}
                    </button>
                </div>

                <div 
                    class="w-1/2 pl-2 ml-auto"
                    v-if="hasOkButton"
                >
                    <button 
                        class="btn btn-blue w-full"
                        @click.prevent="$emit('submit')"
                    >
                        {{ okButtonText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        hasOkButton: {
            type: Boolean,
            required: false,
            default: true
        },
        okButtonText: {
            type: String,
            required: false,
            default: 'Submit'
        },
        hasCancelButton: {
            type: Boolean,
            required: false,
            default: true
        },
        cancelButtonText: {
            type: String,
            required: false,
            default: 'Cancel'
        }
    }
}
</script>