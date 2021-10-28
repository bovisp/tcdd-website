<template>
    <div v-if="!isEmpty(currentContentBuilder)">
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
                :key="tab.id"
                v-for="tab in orderedTabs"
            >
                <template #header>
                    <span> {{ tab.label }} </span>

                    <b-button 
                        icon-right="pencil"
                        type="is-text"
                        size="is-small"
                        @click.prevent="editTab(tab)"
                        :disabled="!showAddTabButton"
                    ></b-button>

                    <b-button 
                        icon-right="close"
                        type="is-text"
                        size="is-small"
                        class="has-text-danger"
                        :disabled="tabLength < 2 || !showAddTabButton"
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
                            v-if="isEmpty(tab.content) === true"
                            :is="`Add${pascalCase(tab.type)}`"
                            :id="currentContentBuilder.id"
                            :tab-part-data-id="data ? data.data.id : null"
                            :type="tab.type"
                            :is-tab-section-part="true"
                        ></component>
                        
                        <component 
                            v-else
                            :is="`Show${pascalCase(tab.type)}`"
                            :id="currentContentBuilder.id"
                            :data="tab.content"
                            :is-tab-section-part="true"
                        ></component>
                    </template>
                </div>
            </b-tab-item>

            <b-tab-item
                class="p-0"
                v-if="showAddTabButton"
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

        <edit-tab-modal 
            :num-tabs="tabLength"
        />

        <add-part-modal 
            v-if="addPartModalActive"
            omit-type="tab"
            @cancel="closeAddPartModal"
            @add="addPartToTab"
        />
    </div>
</template>

<script>
import uuid from 'uuid/v4'
import { pascalCase } from 'change-case'
import { filter, isEmpty, orderBy, find } from 'lodash-es'
import contentBuilderData from '../../../../../mixins/contentBuilder'
import { mapActions } from 'vuex'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        data: {
            type: Object,
            required: false
        },
        showAddTabButton: {
            type: Boolean,
            required: true
        }
    },

    data () {
        return {
            activeTab: 0,
            rerenderKey: 0,
            tabs: [],
            addPartModalActive: false,
            editingTab: {},
            tabAddPart: {}
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
        tabs: {
            deep: true,

            handler () {
                if (isEmpty(this.data)) {
                    this.updateNewForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partial: true,
                        payload: {
                            tabs: this.orderedTabs,
                            activeTab: this.activeTab
                        }
                    })
                } else {
                    this.updateEditForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partDataId: this.data.data.id,
                        type: this.data.builderType.type,
                        payload: {
                            tabs: this.orderedTabs,
                            activeTab: this.activeTab
                        }
                    })
                }
            }
        },

        orderedTabs: {
            deep: true,

            handler () {
                this.rerenderKey += 1
            }
        },

        activeTab () {
            if (isEmpty(this.data)) {
                this.updateActiveTab({
                    adding: true,
                    currentContentBuilder: this.currentContentBuilder,
                    activeTab: this.activeTab
                })
            } else {
                this.updateEditForm({
                    currentContentBuilder: this.currentContentBuilder,
                    adding: false,
                    partDataId: this.data.data.id,
                    payload: {
                        tabs: this.orderedTabs,
                        activeTab: this.activeTab
                    }
                })
            }
        }
    },

    methods: {
        ...mapActions({
            updateNewForm: 'contentbuilder/updateNewForm',
            updateEditForm: 'contentbuilder/updateEditForm',
            updateActiveTab: 'contentbuilder/updateActiveTab'
        }),

        isEmpty,

        pascalCase,

        editTab (tab) {
            window.events.$emit('tabs:edit-tab', tab)
        },

        async updateTab (payload) {
            for await (let tab of this.tabs) {
                if (tab.id === payload.tabToEdit.id) {
                    tab.label = payload.tabToEdit.label

                    tab.order = payload.tabToEdit.order

                    if (parseInt(payload.tabToEdit.order) !== parseInt(payload.originalOrder)) {
                        let tab = find(this.tabs, tab => tab.order === payload.tabToEdit.order && tab.id !== payload.tabToEdit.id)

                        tab.order = payload.originalOrder
                    }
                }
            }
        },

        addNewTab () {
            this.tabs.splice(this.tabs.length, 0, {
                id: uuid(),
                label: 'New tab',
                order: this.tabs.length + 1
            })

            this.activeTab = this.tabLength - 1

            this.rerenderKey += 1
        },

        addPart (tab) {
            this.addPartModalActive = true

            this.tabAddPart = tab

            this.rerenderKey += 1
        },

        addPartToTab (type) {
            this.addPartModalActive = false

            let tab = find(this.tabs, tab => tab.id === this.tabAddPart.id)

            tab.hasContent = true

            tab.type = type

            tab.data = null
        },

        closeAddPartModal () {
            this.addPartModalActive = false

           this.rerenderKey += 1
        },

        async removeTab (tab) {
            if (isEmpty(tab.data) === false) {
                await axios.delete(`${this.urlBase}/api/parts/${tab.type}/${tab.data.data.id}`, {
                    data: {
                        type: tab.type
                    }
                })
            }

            this.tabs = orderBy(filter(this.tabs, t => t.id !== tab.id), ['order'], ['asc'])

            for await (const [index, value] of Object.entries(this.tabs)) {
                this.tabs[index].order = parseInt(index) + 1
            }

            this.activeTab = 0

            this.rerenderKey += 1
        },
    },

    mounted () {
        if (isEmpty(this.data)) {
            this.tabs.push({
                id: uuid(),
                label: 'New tab',
                hasContent: false,
                type: '',
                order: 1,
                content: null
            })
        } else {
            this.tabs = this.data.data.tabs
        }

        window.events.$on('tabs:update-edited-tab', tab => this.updateTab(tab))

        window.events.$on('tabs:cancel-add-part', () => {
            if (!this.partEditStatus) {
                let tab = find(this.tabs, tab => tab.id === this.tabAddPart.id)

                tab.type = ''

                tab.hasContent = false
            }

            this.partEditStatus = false

            this.rerenderKey += 1
        })

        window.events.$on('tabs:add-new-part', async data => {
            for await (let [index, tab] of Object.entries(this.tabs)) {
                if (tab.id === this.tabAddPart.id) {
                    tab.content = data

                    delete tab.data

                    this.rerenderKey += 1
                }
            }
        })
    }
}
</script>