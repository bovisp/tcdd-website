<template>
    <div class="my-4">
        <p 
            class="mb-4 text-center font-light w-full text-xl"
            v-if="typeof data.data !== 'undefined' && data.data.title"
        >
            {{ data.data.title }}
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
                    :id="id"
                    :data="tab.content"
                ></component>
            </b-tab-item>
        </b-tabs>

        <p 
            class="mb-0 mt-2 text-grey-700 w-3/4 mx-auto"
            v-if="typeof data.data !== 'undefined' && data.data.caption"
        >
            <small>{{ data.data.caption }}</small>
        </p>
    </div>
</template>

<script>
import { orderBy } from 'lodash-es'
import { pascalCase } from 'change-case'
import contentBuilderData from '../../../../mixins/contentBuilder'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        data: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            activeTab: 0
        }
    },

    computed: {
        orderedTabs () {
            return orderBy(this.data.data.tabs, ['order'], ['asc'])
        }
    },

    methods: {
        pascalCase
    }
}
</script>