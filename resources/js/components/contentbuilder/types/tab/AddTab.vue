<template>
    <div>
        <form>
            <div class="level">
                <div class="level-left"></div>

                <div class="level-right">
                    <div class="level-item">
                        <b-button
                            type="is-info"
                            size="is-small"
                            @click.prevent="addNewTab"
                        >
                            Add new tab
                        </b-button>
                    </div>
                </div>
            </div>

            <b-field>
                <b-input 
                    placeholder="Add an optional tab title..."
                    size="is-medium"
                    class="borderless-input borderless-input-md"
                    v-model="form.title"
                ></b-input>
            </b-field>

            <b-tabs
                v-model="activeTab"
                type="is-boxed"
                :multiline="true"
                :animated="false"
                :destroy-on-hide="true"
                :key="rerenderKey"
            >
                <template v-for="tab in orderedTabs">
                    <b-tab-item 
                        :label="tab.label"
                        :id="tab.id"
                        :key="tab.id"
                        :value="tab.id"
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

                        <div class="level mb-0" v-if="isEmpty(tab.data) === false && !partEditStatus">
                            <div class="level-left"></div>

                            <div class="level-right">
                                <div class="level-item">
                                    <b-button
                                        type="is-text"
                                        size="is-small"
                                        icon-left="pencil"
                                        @click.prevent="editPart"
                                    >Edit {{ tab.type }}</b-button>
                                </div>

                                <div class="level-item">
                                    <b-button
                                        type="is-text"
                                        class="has-text-danger"
                                        size="is-small"
                                        icon-left="close"
                                        @click.prevent="$buefy.dialog.confirm({
                                            title: `Delete ${tab.type}`,
                                            message: `Are you sure you want to <b>delete</b> this ${tab.type}?`,
                                            confirmText: `Delete ${tab.type}`,
                                            type: 'is-danger',
                                            hasIcon: true,
                                            onConfirm: () => deleteTabContent(tab.type, tab.data)
                                        })"
                                    >Delete</b-button>
                                </div>
                            </div>
                        </div>

                        <div class="h-full w-full flex items-center justify-center">
                            <b-button
                                type="is-text"
                                v-if="!tab.hasContent && !isAddPartActive"
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
                </template>
            </b-tabs>

            <div class="level mb-0">
                <div class="level-left"></div>

                <div class="level-right">
                    <div class="level-item">
                        <!-- <b-button
                            type="is-text"
                            size="is-small"
                            icon-left="pencil"
                            @click.prevent="editPart"
                        >Edit {{ tab.type }}</b-button> -->
                    </div>

                    <div class="level-item">
                        <b-button
                            type="is-text"
                            size="is-small"
                            @click.prevent="cancelAddingPart"
                        >Cancel adding tab</b-button>
                    </div>
                </div>
            </div>
        </form>

        <b-modal
            v-model="isEditModalActive"
            has-modal-card
            trap-focus
            aria-role="dialog"
            aria-label="Example Modal"
            aria-modal
        >
            <form action="">
                <div class="modal-card" style="width: auto">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Edit tab</p>
                        <button
                            type="button"
                            class="delete"
                            @click="closeTabEditModal"
                        />
                    </header>

                    <section class="modal-card-body">
                        <b-field label="Tab title">
                            <b-input
                                type="text"
                                v-model="editTabForm.label"
                                required
                            ></b-input>
                        </b-field>

                        <b-field label="Tab order">
                            <b-select 
                                v-model="editTabForm.order"
                                expanded
                                :disabled="tabLength === 1"
                            >
                                <option
                                    v-for="i in sortedUniq(map(this.tabs, tab => tab.order))"
                                    :value="i"
                                    :key="i"
                                >
                                    {{ i }}
                                </option>
                            </b-select>
                        </b-field>
                    </section>

                    <footer class="modal-card-foot">
                        <b-button
                            label="Cancel"
                            @click="closeTabEditModal" 
                        />
                        <b-button
                            label="Save"
                            type="is-primary" 
                            @click.prevent="updateTab"
                        />
                    </footer>
                </div>
            </form>
        </b-modal>

        <add-part-modal 
            v-if="isAddPartActive"
            omit-type="tab"
            @cancel="closeAddPartModal"
            @add="addPartToTab"
        />
    </div>
</template>

<script>
import uuid from 'uuid/v4'
import { find, filter, isEmpty, slice, map, orderBy, sortedUniq } from 'lodash-es'
import { mapGetters } from 'vuex'
import { pascalCase } from 'change-case'

export default {
    props: {
        editStatus: {
            type: Boolean,
            required: true
        },
        lang: {
            type: String,
            required: true,
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        }
    },

    data () {
        return {
            form: {
                content_builder_type_id: 5,
                title: '',
                caption: '',
                // tabSections: []
            },
            tabs: [{
                id: uuid(),
                label: 'New tab',
                hasContent: false,
                type: '',
                order: 1,
                data: null
            }],
            builderId: null,
            activeTab: 0,
            isEditModalActive: false,
            editingTab: null,
            editTabForm: {
                label: '',
                order: null
            },
            isAddPartActive: false,
            tabAddPart: null,
            rerenderKey: 0,
            partEditStatus: false
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'contentIds'
        }),

        tabLength () {
            return this.tabs.length
        },

        orderedTabs () {
            return orderBy(this.tabs, ['order'], ['asc'])
        }
    },

    watch: {
        tabLength () {
            this.rerenderKey += 1
        },

        orderedTabs: {
            deep: true,

            handler () {
                this.rerenderKey += 1
            }
        }
    },

    methods: {
        // async store () {
        //     let { data } = await axios.post(`${this.urlBase}/api/content-builder/${this.builderId}/tab`, {
        //         content_builder_type_id: this.form.content_builder_type_id,
        //         title: this.form.title,
        //         caption: this.form.caption,
        //         tabSections: this.form.tabSections
        //     })

        //     window.events.$emit('part:created', {
        //         data,
        //         contentBuilderId: this.builderId
        //     })

        //     this.reset()
        // },

        slice,

        pascalCase,
        
        isEmpty,

        map,

        orderBy,

        sortedUniq,

        addNewTab () {
            this.tabs.splice(this.tabs.length, 0, {
                id: uuid(),
                label: 'New tab',
                order: this.tabs.length + 1
            })

            this.activeTab = this.tabs.length - 1
        },

        editTab (tab) {
            this.editingTab = tab

            this.editTabForm.label = tab.label

            this.editTabForm.order = tab.order

            this.isEditModalActive = true
        },

        closeTabEditModal () {
            this.editingTab = null
            this.editTabForm.label = ''
            this.isEditModalActive = false
        },

        updateTab () {
            let tab = find(this.tabs, tab => tab.id === this.editingTab.id)

            tab.label = this.editTabForm.label

            if (parseInt(tab.order) !== parseInt(this.editTabForm.order)) {
                let originalTab = find(this.tabs, tab => parseInt(tab.order) === parseInt(this.editTabForm.order))

                originalTab.order = tab.order

                tab.order = this.editTabForm.order
            }

            this.closeTabEditModal()
        },

        addPartToTab (type) {
            this.isAddPartActive = false

            let tab = find(this.tabs, tab => tab.id === this.tabAddPart.id)

            tab.hasContent = true

            tab.type = type

            tab.data = null
        },

        closeAddPartModal () {
            this.isAddPartActive = false

            this.tabAddPart = null

            this.rerenderKey += 1
        },

        addPart (tab) {
            this.isAddPartActive = true

            this.tabAddPart = tab
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

            this.rerenderKey += 1
        },

        editPart () {
            this.partEditStatus = true

            window.events.$emit('part:force-edit')
        },

        async deleteTabContent (type, data) {
            await axios.delete(`${this.urlBase}/api/parts/${type}/${data.data.id}`)

            let tab = find(this.tabs, tab => tab.data.data.id === data.data.id)

            tab.type = ''

            tab.hasContent = false

            tab.data = null

            this.rerenderKey += 1
        },

        async cancelAddingPart () {
            for await (const tab of this.tabs) {
                if (!isEmpty(tab.data)) {
                    await axios.delete(`${this.urlBase}/api/parts/tabs/cancel`, {
                        data: { tab }
                    })
                }
            }

            window.events.$emit('add-part:cancel', this.builderId)
        },
    },

    mounted () {
        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]

        window.events.$on('tab-content:created', data => {
            let tab = find(this.tabs, tab => tab.id === this.tabAddPart.id)

            tab.data = data

            this.rerenderKey += 1

            // this.tabAddPart = null
        })

        window.events.$on('tab-content:cancel-add', () => {
            if (!this.partEditStatus) {
                let tab = find(this.tabs, tab => tab.id === this.tabAddPart.id)

                tab.type = ''

                tab.hasContent = false
            }

            this.partEditStatus = false

            this.rerenderKey += 1
        })

        window.events.$on('part:edit-cancel', partId => {
            this.partEditStatus = false

            this.rerenderKey += 1
        })
    }
}
</script>