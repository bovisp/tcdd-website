<template>
    <div 
        class="row"
        :class="[ editing ? 'justify-content-end' : '' ]"
    >
        <template v-if="!editing && !editStatus">
            <p 
                class="text-center font-weight-light w-100 h5 mb-3"
                v-if="typeof part.data !== 'undefined' && part.data.title"
            >
                {{ part.data.title }}
            </p>

            <template  v-if="typeof part.data !== 'undefined'">
                <template v-if="fileExtension(part.data.filename[0].file) === 'image'">
                    <img 
                        :src="part.data.filename[0].file" 
                        :alt="part.data.caption"
                        class="d-block mx-auto img-fluid"
                    >
                </template>

                <template v-if="fileExtension(part.data.filename[0].file) === 'video'">
                    <video 
                        controls
                        class="d-block mx-auto img-fluid"
                    >
                        <source 
                            :src="part.data.filename[0].file"
                            type="video/mp4"
                        >
                    </video>
                </template>
            </template>

            <p 
                class="mb-0 mt-2 text-muted font-weight-bold w-75 mx-auto"
                v-if="typeof part.data !== 'undefined' && part.data.caption"
            >
                <small>{{ part.data.caption }}</small>
            </p>
        </template>

        <div
            v-else
            class="my-4"
            :class="editStatus ? 'col-12' : 'col-10 bg-light p-3 rounded'"
        >
            <div
                class="form-group"
            >
                <label 
                    :class="{ 'text-danger': errors.title }"
                    :for="`title-${form.id}`"
                >
                    Title (optional)
                </label>

                <input 
                    type="text" 
                    v-model="form.title"
                    class="form-control"
                    :id="`title-${form.id}`"
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
                    :for="`caption-media-${this.part.id}`"
                >
                    Caption (optional)
                </label>

                <textarea 
                    v-model="form.caption"
                    class="form-control"
                    id="`caption-media-${this.part.id}`"
                    :class="{ 'is-invalid': errors.caption }"
                ></textarea>

                <p
                    v-if="errors.caption"
                    v-text="errors.caption[0]"
                    class="invalid-feedback"
                ></p>
            </div>

            <div 
                class="d-flex my-2"
                v-if="!editStatus"
            >
                <button 
                    class="btn btn-primary btn-sm"
                    @click.prevent="update"
                >
                    Update
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
import fileExtension from '../../../../helpers/fileExtension'
import { merge } from 'lodash-es'

export default {
    props: {
        data: {
            type: Object,
            required: true
        },
        editStatus: {
            type: Boolean,
            required: false,
            default: false
        },
        id: {
            type: Number,
            required: false,
            default: null
        }
    },

    data () {
        return {
            part: {},
            editingTurnedOn: false,
            editing: false,
            errors: [],
            form: {
                title: '',
                caption: ''
            }
        }
    },

    watch: {
        editingTurnedOn () {
            if (this.editingTurnedOn === false) {
                this.editing = false
            }
        },

        form: {
            deep: true,

            handler () {
                this.$emit('edited', merge(this.form, {
                    id: this.id
                }))
            }
        }
    },

    methods: {
        fileExtension,

        update () {
            axios.patch(`/parts/${this.part.id}/media`, this.form)
                .then(({data}) => {
                    this.part = data

                    this.cancel()
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        cancel () {
            this.editing = false

            this.editStatus = false

            this.form.title = this.part.data.title
            this.form.caption = this.part.data.caption

            window.events.$emit('part:edit-cancel')
        },
    },

    mounted () {
        this.part = this.data

        if (this.editStatus) {
            this.editingTurnedOn = true

            this.form.title = this.part.data.title
            this.form.caption = this.part.data.caption
            this.form.filename = this.part.data.filename
        }

        window.events.$on('part:edit', partId => {
            if (this.part.id === partId) {
                this.editing = true
            }
        })

        window.events.$on('series:edit', () => this.editingTurnedOn = !this.editingTurnedOn)

        window.events.$on('tab-content:errors-data', error => {
            if (error.error.id === this.id) {
                this.errors = error.error.error
            }
        })
    }
}
</script>