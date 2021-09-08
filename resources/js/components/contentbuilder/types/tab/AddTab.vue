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
                <template v-for="tab in tabs">
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
                                @click.prevent="editTabTitle(tab)"
                            ></b-button>

                            <b-button 
                                icon-right="close"
                                type="is-text"
                                size="is-small"
                                class="has-text-danger"
                                @click.prevent="$buefy.dialog.confirm({
                                    title: `Delete tab: ${tab.label}`,
                                    message: `Are you sure you want to <b>delete</b> the tab: ${tab.label}`,
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
                                    :edit-status="false"
                                    :data="tab.data"
                                ></component>
                            </template>
                        </div>
                    </b-tab-item>
                </template>
            </b-tabs>
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
    <!-- <div>
        <div
            class="mb-4"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
                :class="{ 'text-red-500': errors.title }"
                for="title"
            >
                {{ trans('js_components_contentbuilder_types_tab_addtab.tabparttitle') }}
            </label>

            <input 
                type="text" 
                v-model="form.title"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                id="title"
                :class="{ 'border-red-500': errors.title }"
            >

            <p
                v-if="errors.title"
                v-text="errors.title[0]"
                class="text-red-500 text-xs"
            ></p>
        </div>

        <div
            class="mb-4"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
                :class="{ 'text-red-500': errors.caption }"
                for="caption"
            >
                {{ trans('js_components_contentbuilder_types_tab_addtab.tabpartcaption') }}
            </label>

            <textarea 
                v-model="form.caption"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                id="caption"
                :class="{ 'border-red-500': errors.caption }"
            ></textarea>

            <p
                v-if="errors.caption"
                v-text="errors.caption[0]"
                class="text-red-500 text-xs"
            ></p>
        </div>

        <hr class="my-6">

        <template v-if="addingTabSection">
            <new-tab-section 
                v-for="section in form.tabSections"
                :key="section.id"
                :data="section"
                :lang="lang"
                @canceladd="cancelAdd"
                :content-builder-id="contentBuilderId"
            />
        </template>

        <button 
            class="btn w-full btn-outline btn-sm text-sm mb-12"
            @click.prevent="addTabSection"
        >
            {{ trans('js_components_contentbuilder_types_tab_addtab.addatab') }}
        </button>

        <div class="flex">
            <button 
                class="btn btn-blue btn-sm text-sm"
                @click.prevent="store"
                :disabled="form.tabSections.length === 0"
            >
                {{ trans('js_components_contentbuilder_types_tab_addtab.createtabpart') }}
            </button>
            
            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="cancel"
            >
                {{ trans('js_components_contentbuilder_types_tab_addtab.cancel') }}
            </button>
        </div> -->
    </div>
</template>

<script>
import uuid from 'uuid/v4'
import { find, filter, isEmpty, slice } from 'lodash-es'
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
                data: null
            }],
            // addingTabSection: false,
            // order: 1,
            builderId: null,
            activeTab: 0,
            isEditModalActive: false,
            editingTab: null,
            editTabForm: {
                label: ''
            },
            isAddPartActive: false,
            tabAddPart: null,
            rerenderKey: 0
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'contentIds'
        }),

        tabLength () {
            return this.tabs.length
        }
    },

    watch: {
        tabLength () {
            this.rerenderKey += 1
        }
    },

    methods: {
        // addTabSection () {
        //     if (!this.addingTabSection) {
        //         this.addingTabSection = true
        //     }

        //     this.form.tabSections.push({
        //         id: uuid(),
        //         title: '',
        //         order: this.order,
        //         data: {},
        //         type: ''
        //     })

        //     this.order += 1
        // },

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

        // cancel () {
        //     this.reset()
        // },

        // async reset () {
        //     for await (const [index, tab] of this.form.tabSections.entries()) {
        //         if (isEmpty(tab.data)) {
        //             await axios.delete(`${this.urlBase}/api/parts/tab-section-parts`, {
        //                 data: { tab }
        //             })
        //         }
        //     }

        //     window.events.$emit('add-part:cancel', this.builderId)
        // },

        // cancelAdd (sectionId) {
        //     this.form.tabSections = filter(this.form.tabSections, section => {
        //         return sectionId !== section.id
        //     })

        //     if (this.form.tabSections === 0) {
        //         this.addingTabSection = false
        //     }
        // }
        slice,

        pascalCase,
        
        isEmpty,

        addNewTab () {
            this.tabs.splice(this.tabs.length, 0, {
                id: uuid(),
                label: 'New tab'
            })

            this.activeTab = this.tabs.length - 1
        },

        editTabTitle (tab) {
            this.editingTab = tab

            this.editTabForm.label = tab.label

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
        },

        addPart (tab) {
            this.isAddPartActive = true

            this.tabAddPart = tab
        },

        async removeTab (tab) {
            await axios.delete(`${this.urlBase}/api/parts/${tab.type}/${tab.data.data.id}`, {
                data: {
                    type: tab.type
                }
            })

            this.tabs = filter(this.tabs, t => t.id !== tab.id)

            this.rerenderKey += 1
        }
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
            let tab = find(this.tabs, tab => tab.id === this.tabAddPart.id)

            tab.type = ''

            tab.hasContent = false

            this.rerenderKey += 1
        })

        // this.tabs[0] = {
        //     initial: true,
        //     label: 'Add new tab',
        //     id: uuid()
        // }

        // window.events.$on('tab-content:section', section => {
        //     let sectionToUpdate = find(this.form.tabSections, s => section.id === s.id)

        //     if (sectionToUpdate) {
        //         sectionToUpdate.title = section.title
        //     }
        // })

        // window.events.$on('tab-content:section-data', section => {
        //     let sectionToUpdate = find(this.form.tabSections, s => section.id === s.id)
            
        //     if (sectionToUpdate) {
        //         sectionToUpdate.data = section.data
        //         sectionToUpdate.type = section.type
        //     }
        // })
    }
}
</script>