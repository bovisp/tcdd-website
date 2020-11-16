<template>
    <div 
        class="flex flex-col w-full"
        :class="[ editing ? 'items-end' : '' ]"
    >
        <template v-if="!editing">
            <p 
                class="mb-0 text-center font-light w-full text-xl"
                v-if="typeof part.data !== 'undefined' && part.data.title"
            >
                {{ part.data.title }}
            </p>

            <image-animator 
                :data="part"
                :fluid="true"
            />

            <p 
                class="mb-0 mt-2 text-gray-700 w-3/4 mx-auto"
                v-if="typeof part.data !== 'undefined' && part.data.caption"
            >
                <small>{{ part.data.caption }}</small>
            </p>
        </template>

        <div
            v-else
            class="my-6"
            :class="editStatus ? 'w-full' : 'w-10/12 bg-gray-100 p-4 rounded'"
        >
            <div
                class="mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.title }"
                    for="title-animation"
                >
                    Title (optional)
                </label>

                <input 
                    type="text" 
                    v-model="form.title"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="title-animation"
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
                    for="caption-animation"
                >
                    Caption (optional)
                </label>

                <textarea 
                    v-model="form.caption"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="caption-animation"
                    :class="{ 'border-red-500': errors.caption }"
                ></textarea>

                <p
                    v-if="errors.caption"
                    v-text="errors.caption[0]"
                    class="text-red-500 text-xs"
                ></p>
            </div>

            <div 
                class="flex my-2"
                v-if="!editStatus"
            >
                <button 
                    class="btn btn-blue btn-sm text-sm"
                    @click.prevent="update"
                >
                    Update
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm ml-auto"
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
        },
        contentBuilderId: {
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
        async update () {
            let { data } = await axios.patch(`${this.urlBase}/api/parts/${this.part.id}/animation`, this.form)
                    
            this.part = data

            this.cancel()
        },

        cancel () {
            this.editing = false

            this.editStatus = false

            this.form.title = this.part.data.title
            this.form.caption = this.part.data.caption
            this.form.images = this.part.data.images

            window.events.$emit('part:edit-cancel', this.part.id)
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

                this.form.title = this.part.data.title
                this.form.caption = this.part.data.caption
                this.form.images = this.part.data.images
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