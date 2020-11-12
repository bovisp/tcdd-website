<template>
    <div>
        <div
            class="form-group"
        >
            <label 
                :class="{ 'text-danger': errors.title }"
                for="title"
            >
                Title (optional)
            </label>

            <input 
                type="text" 
                v-model="form.title"
                class="form-control"
                id="title"
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
                Caption (optional)
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

        <hr class="my-5">

        <template v-if="addingTabSection">
            <new-tab-section 
                v-for="section in form.tabSections"
                :key="section.id"
                :data="section"
            />
        </template>

        <button 
            class="btn btn-block btn-outline-secondary my-5"
            @click="addTabSection"
        >
            Add a tab
        </button>

        <div class="form-group mb-0 d-flex">
            <button 
                class="btn btn-primary"
                @click.prevent="store"
                :disabled="form.tabSections.length === 0"
            >
                Create tab part
            </button>
            
            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                Cancel
            </button>
        </div>
    </div>
</template>

<script>
import uuid from 'uuid/v4'
import { find } from 'lodash-es'

export default {
    props: {
        seriesId: {
            type: Number,
            required: true
        },
        editStatus: {
            type: Boolean,
            required: true
        }
    },

    data () {
        return {
            form: {
                content_builder_type_id: 4,
                title: '',
                caption: '',
                tabSections: []
            },
            errors: [],
            addingTabSection: false,
            order: 1
        }
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

        store () {
            axios.post(`/series/${this.seriesId}/tab`, {
                content_builder_type_id: this.form.content_builder_type_id,
                title: this.form.title,
                caption: this.form.caption,
                tabSections: this.form.tabSections
            })
                .then(({data}) => {
                    window.events.$emit('part:created', data)

                    this.reset()
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        async cancel () {
            this.reset()
        },

        reset () {
            window.events.$emit('add-part:cancel')
        }
    },

    mounted () {
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