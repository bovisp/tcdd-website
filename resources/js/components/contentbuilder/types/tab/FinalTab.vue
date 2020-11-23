<template>
    <div class="my-4">
        <p 
            class="mb-4 text-center font-light w-full text-xl"
            v-if="typeof part.data !== 'undefined' && part.data.title"
        >
            {{ part.data.title }}
        </p>

        <div class="w-full">
            <tabs
                v-if="typeof part.data !== 'undefined'"
            >
                <tab
                    v-for="(section, index) in part.data.tabSections"
                    :key="section.id"
                    :name="section.title"
                    :selected="isActive(section.id, index)"
                >
                    <component 
                        :is="`Show${ucfirst(section.type)}`"
                        :content-builder-id="contentBuilderId"
                        :edit-status="false"
                        :data="section.content"
                    ></component>
                </tab>
            </tabs>
        </div>

        <p 
            class="mb-0 mt-2 text-grey-700 w-3/4 mx-auto"
            v-if="typeof part.data !== 'undefined' && part.data.caption"
        >
            <small>{{ part.data.caption }}</small>
        </p>
    </div>
</template>

<script>
import ucfirst from '../../../../helpers/ucfirst'

export default {
    props: {
        part: {
            type: Object,
            required: true
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        }
    },

    data () {
        return {
            tabClicked: null
        }
    },

    methods: {
        ucfirst,

        isActive (sectionId, index) {
            if (this.tabClicked === null && index === 0) {
                return true
            }

            return this.tabClicked === index
        }
    }
}
</script>