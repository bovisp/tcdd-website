<template>
    <div 
        class="flex flex-col w-full"
        :class="[ editing ? 'items-end' : '' ]"
    >
        <template v-if="!editing && !isEmpty(part)">
            <component 
                :is="`Final${ pascalCase(part.builderType.type) }`"
                :part="part"
            ></component>
        </template>

        <!-- <div
            v-else
            class="w-10/12 my-6 bg-gray-100 p-4"
        >
            <div
                class="mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.title }"
                    for="title-tab"
                >
                    {{ trans('js_components_contentbuilder_types_tab_showtab.tabparttitle') }}
                </label>

                <input 
                    type="text" 
                    v-model="form.title"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="title-tab"
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
                    {{ trans('js_components_contentbuilder_types_tab_showtab.tabpartcaption') }}
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

            <hr class="mt-12 mb-4">

            <h3>
                {{ trans('js_components_contentbuilder_types_tab_showtab.sections') }}
            </h3>

            <edit-tab-section
                v-for="section in part.data.tabSections"
                :key="section.id"
                :section="section"
                :lang="lang"
            />

            <div 
                class="alert alert-red"
                v-if="form.tabSections.length === 0"
            >
                {{ trans('js_components_contentbuilder_types_tab_showtab.maustaddtabcontent') }}
            </div>

            <template v-if="addingTabSection">
                <new-tab-section 
                    :data="newSection"
                    :part-id="part.data.id"
                    :lang="lang"
                />
            </template>

            <button 
                class="btn w-full btn-outline btn-sm text-sm my-12"
                @click="addTabSection"
                v-if="!addingTabSection"
            >
                {{ trans('js_components_contentbuilder_types_tab_showtab.addatab') }}
            </button>

            <div class="flex my-2">
                <button 
                    class="btn btn-blue btn-sm text-sm"
                    @click.prevent="update"
                    :disabled="form.tabSections.length === 0"
                >
                    {{ trans('js_components_contentbuilder_types_tab_showtab.updatetabpart') }}
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm ml-auto"
                    @click.prevent="cancel"
                >
                    {{ trans('js_components_contentbuilder_types_tab_showtab.cancel') }}
                </button>
            </div>
        </div> -->
    </div>
</template>

<script>
import ucfirst from '../../../../helpers/ucfirst'
import uuid from 'uuid/v4'
import { map, findIndex, forEach, filter, indexOf, isEmpty } from 'lodash-es'
import { pascalCase } from 'change-case'

export default {
    props: {
        data: {
            type: Object,
            required: true
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        },
        lang: {
            type: String,
            required: false,
            default: ''
        }
    },

    data () {
        return {
            part: {},
            editingTurnedOn: false,
            editing: false,
            tabClicked: null,
            form: {
                title: '',
                caption: '',
                tabSections: []
            },
            addingTabSection: false,
            order: 1,
            newSection: {},
            hasTabSections: true,
            deletedSections: []
        }
    },

    watch: {
        editingTurnedOn () {
            if (this.editingTurnedOn === false) {
                this.editing = false
            }
        }
    },

    methods: {
        ucfirst,

        pascalCase,

        isEmpty,

        addTabSection () {
            if (!this.addingTabSection) {
                this.addingTabSection = true
            }

            this.newSection = {
                id: uuid(),
                title: '',
                order: this.order,
                data: {},
                type: ''
            }

            this.order += 1
        },

        isActive (sectionId, index) {
            if (this.tabClicked === null && index === 0) {
                return true
            }

            return this.tabClicked === index
        },

        async update () {
            if (this.form.tabSections.length === 0) {
                this.hasTabSections === false

                return
            }

            let { data } = await axios.patch(`${this.urlBase}/api/parts/${this.part.id}/tab`, this.form)

            this.part = data

            this.isActive(this.part.data.tabSections[0]['id'], 0)

            this.cancel()

            if (this.errors) {
                forEach(Object.keys(this.errors), error => {
                    if (error.split('.')[0].indexOf('tabSections') >= 0) {
                        let index = parseInt(error.split('.')[1])

                        if (error.split('.')[2] === 'data') {
                            window.events.$emit('tab-content:errors-data', {
                                id: this.form.tabSections[index]['id'],
                                error: this.errors[error]
                            })
                        } else {
                            window.events.$emit('tab-content:errors', {
                                id: this.form.tabSections[index]['id'],
                                error: this.errors[error]
                            })
                        }
                    }
                })
            }
        },

        cancel () {
            this.editing = false

            this.form.title = this.part.data.title
            this.form.caption = this.part.data.caption

            this.form.tabSections = map(this.part.data.tabSections, section => {
                return {
                    id: section.id,
                    title: section.title
                }
            })

            this.deletedSections = []

            window.events.$emit('part:edit-cancel', this.part.id)
        },
    },

    async mounted () {
        this.part = this.data

        this.part.tabPartId = this.part.data.tabSections[0].tab_part_id

        this.form.title = this.part.data.title
        this.form.caption = this.part.data.caption

        this.form.tabSections = map(this.part.data.tabSections, section => {
            return {
                id: section.id,
                title: section.title
            }
        })

        this.order = this.form.tabSections.length + 1

        window.events.$on('part:edit', partId => {
            if (this.part.id === partId) {
                this.editing = true

                this.tabClicked = 0
            }
        })

        window.events.$on('series:edit', () => this.editingTurnedOn = !this.editingTurnedOn)

        window.events.$on('tab-content:edit-section', async s => {
            let sectionIndex = await findIndex(this.form.tabSections, sect => sect.id === s.id)
            
            if (sectionIndex >= 0) {
                this.form.tabSections[sectionIndex][s.type]  = s.data
            }
        })

        window.events.$on('tab-content:destroy', async sectionId => {
            this.form.tabSections = filter(this.form.tabSections, section => section.id !== sectionId)

            this.part.data.tabSections = filter(this.part.data.tabSections, section => section.id !== sectionId)

            this.deletedSections.push(sectionId)
        })

        window.events.$on('tab-content:section-data', async section => {
            this.form.tabSections.push(section)

            let part = await axios.get(`${this.urlBase}/api/parts/${this.part.id}`)

            this.part = part.data

            this.part.data.tabSections = filter(this.part.data.tabSections, section => indexOf(this.deletedSections, section.id) === -1)
        })

        window.events.$on('tab-content:section', async data => {
            let sectionIndex = await findIndex(this.form.tabSections, sect => sect.id === data.id)
            
            if (sectionIndex >= 0) {
                this.form.tabSections[sectionIndex]['title']  = data.title  
            }
        })

        window.events.$on('tab-content:cancel-adding-tab-section', async cancelAddingTabSection => {
            this.addingTabSection = false
        })
    }
}
</script>