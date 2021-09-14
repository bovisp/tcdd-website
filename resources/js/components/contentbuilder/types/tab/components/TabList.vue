<template>
    <b-tabs
        v-model="activeTab"
        type="is-boxed"
        :multiline="true"
        :animated="false"
        :destroy-on-hide="true"
        :key="rerenderKey"
        class="mb-0"
    >
        <b-tab-item 
            :label="tab.label"
            :id="tab.id"
            :key="tab.id"
            :value="tab.id"
            v-for="tab in orderedTabs"
        >
            <template #header>
                <span> {{ tab.label }} </span>

                <b-button 
                    icon-right="pencil"
                    type="is-text"
                    size="is-small"
                    @click.prevent="editTab(tab)"
                ></b-button>

                <b-button 
                    icon-right="close"
                    type="is-text"
                    size="is-small"
                    class="has-text-danger"
                    :disabled="tabLength < 2"
                    @click.prevent="$buefy.dialog.confirm({
                        title: `Delete tab: ${tab.label}`,
                        message: `Are you sure you want to <b>delete</b> the tab: ${tab.label}?`,
                        confirmText: 'Delete tab',
                        type: 'is-danger',
                        hasIcon: true,
                        onConfirm: () => removeTab(tab)
                    })"
                ></b-button>
            </template>

            <div class="h-full w-full flex items-center justify-center">
                <b-button
                    type="is-text"
                    v-if="!tab.hasContent && !addPartModalActive"
                    @click.prevent="addPart(tab)"
                >
                    Add content to {{ tab.label }}
                </b-button>

                <template v-if="tab.type">
                    <component 
                        v-if="isEmpty(tab.data) === true"
                        :is="`Add${pascalCase(tab.type)}`"
                        :edit-status="false"
                        :create-button-text="trans('generic.create')"
                        :lang="lang"
                        :is-tab-section-part="true"
                    ></component>
                    
                    <component 
                        v-else
                        :is="`Show${pascalCase(tab.type)}`"
                        :edit-status="partEditStatus"
                        :data="tab.data"
                        :is-tab-section-part="true"
                    ></component>
                </template>
            </div>
        </b-tab-item>

        <b-tab-item
            class="p-0"
        >
            <template #header>
                <b-button 
                    icon-right="plus"
                    type="is-text"
                    size="is-small"
                    title="Add new tab"
                    @click.prevent="addNewTab"
                ></b-button>
            </template>
        </b-tab-item>
    </b-tabs>
</template>

<script>
import uuid from 'uuid/v4'
import { pascalCase } from 'change-case'
import { filter, isEmpty, orderBy } from 'lodash-es'

export default {
    data () {
        return {
            rerenderKey: 0,
            tabs: [{
                id: uuid(),
                label: 'New tab',
                hasContent: false,
                type: '',
                order: 1,
                data: null
            }],
            editingTab: null,
            isEditModalActive: false,
            activeTab: 0,
            addPartModalActive: false,
        }
    },

    computed: {
        orderedTabs () {
            return orderBy(this.tabs, ['order'], ['asc'])
        },

        tabLength () {
            return this.tabs.length
        }
    },

    watch: {
        orderedTabs: {
            deep: true,

            handler () {
                this.rerenderKey += 1
            }
        }
    },

    methods: {
        isEmpty,

        pascalCase,

        editTab (tab) {
            window.events.$emit('tabs:edit-tab', tab)
        },

        async removeTab (tab) {
            if (isEmpty(tab.data) === false) {
                await axios.delete(`${this.urlBase}/api/parts/${tab.type}/${tab.data.data.id}`, {
                    data: {
                        type: tab.type
                    }
                })
            }

            this.tabs = filter(this.tabs, t => t.id !== tab.id)

            this.activeTab = 0

            this.rerenderKey += 1
        },

        addNewTab () {
            this.tabs.splice(this.tabs.length, 0, {
                id: uuid(),
                label: 'New tab',
                order: this.tabs.length + 1
            })

            this.activeTab = this.tabLength - 1

            this.rerenderKey += 1

            this.$emit('tabs:update-tab-count', this.tabs)
        },
    },

    mounted () {
        window.events.$on('tabs:add-part-modal', () => {
            this.addPartModalActive = !this.addPartModalActive
        })

        window.events.$on('tabs:update-tab-list', tabs => {
            this.tabs = tabs

            this.rerenderKey += 1
        })
    }
}
</script>