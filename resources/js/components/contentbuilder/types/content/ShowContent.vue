<template>
    <div 
        class="flex w-full mt-4"
        :class="[ editing ? 'justify-end' : '' ]"
    >
        <!-- <div 
            v-if="editing"
            class="my-6"
            :class="editStatus ? 'w-full' : 'w-10/12 bg-gray-100 p-4 rounded'"
        > -->

        <div 
            v-if="editing"
            :class="[ isTabSectionPart ? '' : '' ]"
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

                <!-- <div 
                    class="flex my-2"
                    v-if="!editStatus && editing"
                > -->

                <div class="flex my-2">
                    <button 
                        class="button is-small is-info"
                        @click.prevent="update"
                    >
                        {{ trans('generic.update') }}
                    </button>

                    <button 
                        class="button is-small is-text has-text-dark ml-auto"
                        @click.prevent="cancel"
                    >
                        {{ trans('generic.cancel') }}
                    </button>
                </div>
            </form>
        </div>
        
        <template
            v-if="!editStatus && !editing"
        >
            <div
                class="content"
                v-html="content"
            ></div>
        </template>
    </div>
</template>

<script>
import { merge, isEmpty } from 'lodash-es'
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
        },
        isTabSectionPart: {
            type: Boolean,
            required: false,
            default: false
        },
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
            if (!isEmpty(this.part.data)) {
                return this.part.data.content
                    .replace(/<p><br><\/p>/g, '')
                    .replace(/<p class="ql-align-justify"><br><\/p>/g, '')
                    .replace(/<p class="ql-align-right"><br><\/p>/g, '')
                    .replace(/<p class="ql-align-left"><br><\/p>/g, '')
            }

            return ''
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
            let { data } = await axios.patch(`${this.urlBase}/api/parts/${this.part.data.id}/content`, this.form)

            this.part.data = data

            this.cancel()
        },

        cancel () {
            this.editing = false
            
            this.form.content = this.part.data.content
        },
    },

    mounted () {
        // if (this.data.hasOwnProperty('content')) {
            // this.part.data = this.data
        // } else {
            this.part = this.data
        // }

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

        window.events.$on('part:force-edit', () => {
            this.editing = true

            this.part = this.data

            this.form.content = this.part.data.content
        })
    }
}
</script>

<style>
.content p:last-child {
    margin-bottom: 0 !important;
}
</style>