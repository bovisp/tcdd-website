<template>
    <div>
        <form>
           <b-field>
                <b-input 
                    placeholder="Add an optional tab title..."
                    size="is-medium"
                    class="borderless-input borderless-input-md"
                    v-model="form.title"
                ></b-input>
            </b-field>

            <tab-list 
                :tabs="form.tabs"
                :lang="lang"
                @tabs:update-tab-count="updateTabs"
            />

            <b-field>
                <b-input 
                    placeholder="Add an optional tab caption..."
                    class="borderless-input"
                    v-model="form.caption"
                ></b-input>
            </b-field>

            <div class="level mb-0">
                <div class="level-left">
                    <div class="level-item">
                        <b-button
                            type="is-info"
                            size="is-small"
                            icon-left="pencil"
                            @click.prevent="store"
                        >Create tab</b-button>
                    </div>
                </div>

                <div class="level-right">
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

        <edit-tab-modal 
            :num-tabs="this.form.tabs.length"
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
import { find, isEmpty, slice, map, orderBy, sortedUniq } from 'lodash-es'
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
                tabs: []
            },
            builderId: null,
            tabAddPart: null,
            addPartModalActive: false
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'contentIds'
        })
    },

    watch: {
        isAddPartActive () {
            window.events.$emit('tabs:add-part-modal')
        }
    },

    methods: {
        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/content-builder/${this.builderId}/tab`, {
                content_builder_type_id: this.form.content_builder_type_id,
                title: this.form.title,
                caption: this.form.caption,
                tabSections: this.tabs
            })

            window.events.$emit('part:created', {
                data,
                contentBuilderId: this.builderId
            })
        },

        slice,

        pascalCase,
        
        isEmpty,

        map,

        orderBy,

        sortedUniq,

        updateTab (payload) {
            let tab = find(this.form.tabs, tab => tab.id === payload.tabToEdit.id)

            tab.label = payload.tabToEdit.label

            tab.order = payload.tabToEdit.order

            if (parseInt(payload.tabToEdit.order) !== parseInt(payload.originalOrder)) {
                let tab = find(this.form.tabs, tab => tab.order === payload.tabToEdit.order && tab.id !== payload.tabToEdit.id)

                console.log(tab)

                tab.order = payload.originalOrder
            }

            window.events.$emit('tabs:update-tab-list', this.form.tabs)
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

        updateTabs (tabs) {
            this.form.tabs = tabs
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

        window.events.$on('tabs:update-edited-tab', tab => this.updateTab(tab))
    }
}
</script>