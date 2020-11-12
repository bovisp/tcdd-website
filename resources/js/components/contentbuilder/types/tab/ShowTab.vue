<template>
    <div 
        class="row"
        :class="[ editing ? 'justify-content-end' : '' ]"
    >
        <template v-if="!editing">
            <p 
                class="mb-0 text-center font-weight-light w-100 h5 mb-3"
                v-if="typeof part.data !== 'undefined' && part.data.title"
            >
                {{ part.data.title }}
            </p>

            <div class="w-100">
                <ul 
                    class="nav nav-tabs" 
                    :id="`tab-part-${this.part.id}`" 
                    role="tablist"
                    v-if="typeof part.data !== 'undefined'"
                >
                    <li 
                        class="nav-item"
                        v-for="(section, index) in part.data.tabSections"
                        :key="section.id"
                    >
                        <a 
                            class="nav-link" 
                            :class="{'active': isActive(section.id, index)}"
                            :id="`section-${section.id}-tab`"
                            data-toggle="tab"
                            :href="`#section-${section.id}`"
                            role="tab"
                            :aria-controls="`section-${section.id}`"
                            :aria-selected="isActive(section.id, index)"
                            @click="tabClicked = index"
                        >
                            {{ section.title }}
                        </a>
                    </li>
                </ul>

                <div 
                    class="tab-content rounded-bottom border-left border-right border-bottom"
                    v-if="typeof part.data !== 'undefined'"
                >
                    <div 
                        v-for="(section, index) in part.data.tabSections"
                        :key="section.id"
                        class="tab-pane p-3"
                        :class="{'active': isActive(section.id, index)}"
                        :id="`section-${section.id}`" 
                        role="tabpanel" 
                        :aria-labelledby="`section-${section.id}-tab`"
                    >
                        <div class="px-3">
                            <component 
                                :is="`Show${ucfirst(section.type)}`"
                                :series-id="1"
                                :edit-status="false"
                                :data="section.content"
                            ></component>
                        </div>
                    </div>
                </div>
            </div>

            <p 
                class="mb-0 mt-2 text-muted font-weight-bold w-75 mx-auto"
                v-if="typeof part.data !== 'undefined' && part.data.caption"
            >
                <small>{{ part.data.caption }}</small>
            </p>
        </template>

        <div
            v-else
            class="col-10 my-4 bg-light"
        >
            <div
                class="form-group"
            >
                <label 
                    :class="{ 'text-danger': errors.title }"
                    for="title-tab"
                >
                    Tab part title (optional)
                </label>

                <input 
                    type="text" 
                    v-model="form.title"
                    class="form-control"
                    id="title-tab"
                    :class="{ 'is-invalid': errors.title }"
                >

                <p
                    v-if="errors.title"
                    v-text="errors.title[0]"
                    class="invalid-feedback"
                ></p>
            </div>

            <div
                class="form-group"
            >
                <label 
                    :class="{ 'text-danger': errors.caption }"
                    for="caption"
                >
                    Tab part caption (optional)
                </label>

                <textarea 
                    v-model="form.caption"
                    class="form-control"
                    id="caption"
                    :class="{ 'is-invalid': errors.caption }"
                ></textarea>

                <p
                    v-if="errors.caption"
                    v-text="errors.caption[0]"
                    class="invalid-feedback"
                ></p>
            </div>

            <hr class="mt-5 mb-3">

            <h3>Sections</h3>

            <edit-tab-section
                v-for="section in part.data.tabSections"
                :key="section.id"
                :section="section"
            />

            <div 
                class="alert alert-danger"
                v-if="form.tabSections.length === 0"
            >
                You must add some tab content first.
            </div>

            <template v-if="addingTabSection">
                <new-tab-section 
                    :data="newSection"
                    :part-id="part.data.id"
                />
            </template>

            <button 
                class="btn btn-block btn-outline-secondary my-5"
                @click="addTabSection"
                v-if="!addingTabSection"
            >
                Add a tab
            </button>

            <div class="d-flex my-2">
                <button 
                    class="btn btn-primary btn-sm"
                    @click.prevent="update"
                    :disabled="form.tabSections.length === 0"
                >
                    Update tab part
                </button>

                <button 
                    class="btn btn-text btn-sm ml-auto"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import ucfirst from '../../../../helpers/ucfirst'
import uuid from 'uuid/v4'
import { map, findIndex, forEach, filter, find, indexOf } from 'lodash-es'

export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            part: {},
            editingTurnedOn: false,
            editing: false,
            tabClicked: null,
            errors: [],
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

        update () {
            if (this.form.tabSections.length === 0) {
                this.hasTabSections === false

                return
            }

            axios.patch(`/parts/${this.part.id}/tab`, this.form)
                .then(({data}) => {
                    this.part = data

                    this.isActive(this.part.data.tabSections[0]['id'], 0)

                    this.cancel()
                }).catch(error => {
                    console.log(error)
                    this.errors = error.response.data.errors

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
                })
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

            window.events.$emit('part:edit-cancel')
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

            let part = await axios.get(`/parts/${this.part.id}`)

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