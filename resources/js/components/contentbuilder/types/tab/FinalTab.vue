<template>
    <div class="my-4">
        <p 
            class="mb-4 text-center font-light w-full text-xl"
            v-if="typeof part.data !== 'undefined' && part.data.title"
        >
            {{ part.data.title }}
        </p>

        <b-tabs
            v-model="activeTab"
            type="is-boxed"
            :multiline="true"
            :animated="false"
            :destroy-on-hide="true"
        >
            <b-tab-item 
                :label="tab.label"
                :key="tab.id"
                v-for="tab in orderedTabs"
            >
                <component 
                    :is="`Show${pascalCase(tab.type)}`"
                    :content-builder-id="contentBuilderId"
                    :edit-status="false"
                    :data="tab.content"
                ></component>
            </b-tab-item>
        </b-tabs>

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
import { orderBy } from 'lodash-es'
import { pascalCase } from 'change-case'

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
            activeTab: 0
        }
    },

    computed: {
        orderedTabs () {
            return orderBy(this.part.data.tabSections, ['order'], ['asc'])
        }
    },

    methods: {
        ucfirst,

        pascalCase,

        isActive (sectionId, index) {
            if (this.tabClicked === null && index === 0) {
                return true
            }

            return this.tabClicked === index
        }
    }
}
</script>