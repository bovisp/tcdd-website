<template>
    <div 
        class="flex"
        :class="[ editing ? 'justify-end' : '' ]"
    >
        <div 
            v-if="editing"
            class="my-6"
            :class="editStatus ? 'w-full' : 'w-10/12 bg-gray-100 p-4 rounded'"
        >
            <form>
                <vue-editor 
                    v-model="form.content"
                ></vue-editor>

                <p
                    v-if="errors.content"
                    v-text="errors.content[0]"
                    class="text-xs text-red-500 mt-2"
                ></p>

                <div 
                    class="flex my-2"
                    v-if="!editStatus && editing"
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
            </form>
        </div>
        
        <template
            v-if="!editStatus && !editing"
        >
            <div
                v-if="typeof part !== 'undefined' && typeof part.data !== 'undefined'"
                class="content"
                v-html="content"
            ></div>
        </template>
    </div>
</template>

<script>
import { merge } from 'lodash-es'
import { VueEditor, Quill } from 'vue2-editor'

export default {
    components: {
        VueEditor
    },

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
                content: ''
            }
        }
    },

    computed: {
        content () {
            return this.part.data.content
                .replace(/<p><br><\/p>/g, '')
                .replace(/<p class="ql-align-justify"><br><\/p>/g, '')
                .replace(/<p class="ql-align-right"><br><\/p>/g, '')
                .replace(/<p class="ql-align-left"><br><\/p>/g, '')
        },
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
            let { data } = await axios.patch(`${this.urlBase}/api/parts/${this.part.id}/content`, this.form)

            this.part = data

            this.cancel()
        },

        cancel () {
            this.editing = false
            
            this.form.content = this.part.data.content

            window.events.$emit('part:edit-cancel', this.part.id)
        },
    },

    mounted () {
        this.part = this.data

        window.events.$on('part:edit', partId => {
            if (this.part.id === partId) {
                this.editing = true
                this.form.content = this.part.data.content
            }
        })

        if (this.editStatus) {
            this.editing = true
            this.form.content = this.part.data.content
        }

        window.events.$on('series:edit', () => this.editingTurnedOn = !this.editingTurnedOn)

        window.events.$on('tab-content:errors-data', error => {
            if (error.error.id === this.id) {
                this.errors = error.error.error
            }
        })
    }
}
</script>

<style>
.content p:last-child {
    margin-bottom: 0 !important;
}
</style>