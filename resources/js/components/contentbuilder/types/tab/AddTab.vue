<template>
    <div>
        <div
            class="mb-4"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
                :class="{ 'text-red-500': errors.title }"
                for="title"
            >
                Tab part title (optional)
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
                Tab part caption (optional)
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
            Add a tab
        </button>

        <div class="flex">
            <button 
                class="btn btn-blue btn-sm text-sm"
                @click.prevent="store"
                :disabled="form.tabSections.length === 0"
            >
                Create tab part
            </button>
            
            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="cancel"
            >
                Cancel
            </button>
        </div>
    </div>
</template>

<script>
import uuid from 'uuid/v4'
import { find, filter, isEmpty } from 'lodash-es'
import { mapGetters } from 'vuex'

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
                tabSections: []
            },
            addingTabSection: false,
            order: 1,
            builderId: null
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'questions/contentIds'
        })
    },

    methods: {
        addTabSection () {
            if (!this.addingTabSection) {
                this.addingTabSection = true
            }

            this.form.tabSections.push({
                id: uuid(),
                title: '',
                order: this.order,
                data: {},
                type: ''
            })

            this.order += 1
        },

        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/content-builder/${this.builderId}/tab`, {
                content_builder_type_id: this.form.content_builder_type_id,
                title: this.form.title,
                caption: this.form.caption,
                tabSections: this.form.tabSections
            })

            window.events.$emit('part:created', {
                data,
                contentBuilderId: this.builderId
            })

            this.reset()
        },

        cancel () {
            this.reset()
        },

        async reset () {
            for await (const [index, tab] of this.form.tabSections.entries()) {
                if (isEmpty(tab.data)) {
                    await axios.delete(`${this.urlBase}/api/parts/tab-section-parts`, {
                        data: { tab }
                    })
                }
            }

            window.events.$emit('add-part:cancel', this.builderId)
        },

        cancelAdd (sectionId) {
            this.form.tabSections = filter(this.form.tabSections, section => {
                return sectionId !== section.id
            })

            if (this.form.tabSections === 0) {
                this.addingTabSection = false
            }
        }
    },

    mounted () {
        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]

        window.events.$on('tab-content:section', section => {
            let sectionToUpdate = find(this.form.tabSections, s => section.id === s.id)

            if (sectionToUpdate) {
                sectionToUpdate.title = section.title
            }
        })

        window.events.$on('tab-content:section-data', section => {
            let sectionToUpdate = find(this.form.tabSections, s => section.id === s.id)
            
            if (sectionToUpdate) {
                sectionToUpdate.data = section.data
                sectionToUpdate.type = section.type
            }
        })
    }
}
</script>