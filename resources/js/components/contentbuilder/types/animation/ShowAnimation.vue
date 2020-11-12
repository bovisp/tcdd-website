<template>
    <div 
        class="row"
        :class="[ editing ? 'justify-content-end' : '' ]"
    >
        <template v-if="!editing">
            <p 
                class="mb-0 text-center font-weight-light w-100 h5"
                v-if="typeof part.data !== 'undefined' && part.data.title"
            >
                {{ part.data.title }}
            </p>

            <image-animator 
                :data="part"
                :fluid="true"
            />

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
                    for="title-animation"
                >
                    Title (optional)
                </label>

                <input 
                    type="text" 
                    v-model="form.title"
                    class="form-control"
                    id="title-animation"
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
                    for="caption-animation"
                >
                    Caption (optional)
                </label>

                <textarea 
                    v-model="form.caption"
                    class="form-control"
                    id="caption-animation"
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
import { isEmpty, merge } from 'lodash-es'

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
                caption: '',
                images: []
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
        update () {
            axios.patch(`/parts/${this.part.id}/animation`, this.form)
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
            this.form.images = this.part.data.images

            window.events.$emit('part:edit-cancel')
        },
    },

    mounted () {
        this.part = this.data            

        if (this.editStatus) {
            this.editing = true

            this.form.title = this.part.data.title
            this.form.caption = this.part.data.caption
            this.form.images = this.part.data.images
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