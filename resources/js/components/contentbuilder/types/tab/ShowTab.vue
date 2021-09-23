<template>
    <div 
        v-if="!isEmpty(part)"
    >
        <template v-if="!editing && !isEmpty(part)">
            <component 
                :is="`Final${ pascalCase(part.builderType.type) }`"
                :part="part"
            ></component>
        </template>

        <template v-else>
            <edit-tabs 
                :lang="lang"
                :data="part.data"
            />

            <div class="level mt-2">
                <div class="level-left">
                    <div class="level-item">
                        <b-button
                            type="is-info"
                            size="is-small"
                            icon-left="pencil"
                            @click.prevent="update"
                        >Update tab</b-button>
                    </div>
                </div>

                <div class="level-right">
                    <div class="level-item">
                        <b-button
                            type="is-text"
                            size="is-small"
                            @click.prevent="cancelUpdatingTab"
                        >Cancel updating tab</b-button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import ucfirst from '../../../../helpers/ucfirst'
import uuid from 'uuid/v4'
import { map, findIndex, forEach, filter, indexOf, isEmpty, isNumber } from 'lodash-es'
import { pascalCase } from 'change-case'

export default {
    props: {
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        },
        lang: {
            type: String,
            required: false,
            default: ''
        },
        data: {
            type: Object,
            required: true
        },
    },

    data () {
        return {
            form: null,
            builderId: null,
            part: null,
            editing: false
        }
    },

    methods: {
        isEmpty,

        pascalCase,

        update () {
            // 
        },

        async cancelUpdatingTab () {
            for await (const tab of this.part.data.tabSections) {
                if (!isEmpty(tab.content) && !isNumber(tab.id)) {
                    await axios.delete(`${this.urlBase}/api/parts/tabs/cancel`, {
                        data: { tab }
                    })
                }
            }

            this.part.data.tabSections = filter(this.part.data.tabSections, tab => isNumber(tab.id))

            this.editing = false

            window.events.$emit('part:edit-cancel')
        }
    },

    mounted () {
        this.part = this.data

        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]

        window.events.$on('tabs:update-tab-list', tabs => this.form.tabs = tabs)

        window.events.$on('tabs:update-form', form => this.form = form)

        window.events.$on('part:edit', partId => {
            if (this.part.id === partId) {
                this.editing = true
            }
        })

        window.events.$on('turn-editing-off', async () => {
            await this.cancelUpdatingTab()
        })
    }
    // props: {
    //     data: {
    //         type: Object,
    //         required: true
    //     },
    //     contentBuilderId: {
    //         type: Number,
    //         required: false,
    //         default: null
    //     },
    //     lang: {
    //         type: String,
    //         required: false,
    //         default: ''
    //     }
    // },

    // data () {
    //     return {
    //         part: {},
    //         editingTurnedOn: false,
    //         editing: false,
    //         tabClicked: null,
    //         form: {
    //             title: '',
    //             caption: '',
    //             tabSections: []
    //         },
    //         addingTabSection: false,
    //         order: 1,
    //         newSection: {},
    //         hasTabSections: true,
    //         deletedSections: []
    //     }
    // },

    // watch: {
    //     editingTurnedOn () {
    //         if (this.editingTurnedOn === false) {
    //             this.editing = false
    //         }
    //     }
    // },

    // methods: {
    //     ucfirst,

    //     pascalCase,

    //     isEmpty,

    //     addTabSection () {
    //         if (!this.addingTabSection) {
    //             this.addingTabSection = true
    //         }

    //         this.newSection = {
    //             id: uuid(),
    //             title: '',
    //             order: this.order,
    //             data: {},
    //             type: ''
    //         }

    //         this.order += 1
    //     },

    //     isActive (sectionId, index) {
    //         if (this.tabClicked === null && index === 0) {
    //             return true
    //         }

    //         return this.tabClicked === index
    //     },

    //     async update () {
    //         if (this.form.tabSections.length === 0) {
    //             this.hasTabSections === false

    //             return
    //         }

    //         let { data } = await axios.patch(`${this.urlBase}/api/parts/${this.part.id}/tab`, this.form)

    //         this.part = data

    //         this.isActive(this.part.data.tabSections[0]['id'], 0)

    //         this.cancel()

    //         if (this.errors) {
    //             forEach(Object.keys(this.errors), error => {
    //                 if (error.split('.')[0].indexOf('tabSections') >= 0) {
    //                     let index = parseInt(error.split('.')[1])

    //                     if (error.split('.')[2] === 'data') {
    //                         window.events.$emit('tab-content:errors-data', {
    //                             id: this.form.tabSections[index]['id'],
    //                             error: this.errors[error]
    //                         })
    //                     } else {
    //                         window.events.$emit('tab-content:errors', {
    //                             id: this.form.tabSections[index]['id'],
    //                             error: this.errors[error]
    //                         })
    //                     }
    //                 }
    //             })
    //         }
    //     },

    //     cancel () {
    //         this.editing = false

    //         this.form.title = this.part.data.title
    //         this.form.caption = this.part.data.caption

    //         this.form.tabSections = map(this.part.data.tabSections, section => {
    //             return {
    //                 id: section.id,
    //                 title: section.title
    //             }
    //         })

    //         this.deletedSections = []

    //         window.events.$emit('part:edit-cancel', this.part.id)
    //     },
    // },

    // async mounted () {
    //     this.part = this.data

    //     this.part.tabPartId = this.part.data.tabSections[0].tab_part_id

    //     this.form.title = this.part.data.title
    //     this.form.caption = this.part.data.caption

    //     this.form.tabSections = map(this.part.data.tabSections, section => {
    //         return {
    //             id: section.id,
    //             title: section.title
    //         }
    //     })

    //     this.order = this.form.tabSections.length + 1

    //     window.events.$on('part:edit', partId => {
    //         if (this.part.id === partId) {
    //             this.editing = true

    //             this.tabClicked = 0
    //         }
    //     })

    //     window.events.$on('series:edit', () => this.editingTurnedOn = !this.editingTurnedOn)

    //     window.events.$on('tab-content:edit-section', async s => {
    //         let sectionIndex = await findIndex(this.form.tabSections, sect => sect.id === s.id)
            
    //         if (sectionIndex >= 0) {
    //             this.form.tabSections[sectionIndex][s.type]  = s.data
    //         }
    //     })

    //     window.events.$on('tab-content:destroy', async sectionId => {
    //         this.form.tabSections = filter(this.form.tabSections, section => section.id !== sectionId)

    //         this.part.data.tabSections = filter(this.part.data.tabSections, section => section.id !== sectionId)

    //         this.deletedSections.push(sectionId)
    //     })

    //     window.events.$on('tab-content:section-data', async section => {
    //         this.form.tabSections.push(section)

    //         let part = await axios.get(`${this.urlBase}/api/parts/${this.part.id}`)

    //         this.part = part.data

    //         this.part.data.tabSections = filter(this.part.data.tabSections, section => indexOf(this.deletedSections, section.id) === -1)
    //     })

    //     window.events.$on('tab-content:section', async data => {
    //         let sectionIndex = await findIndex(this.form.tabSections, sect => sect.id === data.id)
            
    //         if (sectionIndex >= 0) {
    //             this.form.tabSections[sectionIndex]['title']  = data.title  
    //         }
    //     })

    //     window.events.$on('tab-content:cancel-adding-tab-section', async cancelAddingTabSection => {
    //         this.addingTabSection = false
    //     })
    // }
}
</script>