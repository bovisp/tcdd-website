<template>
    <div 
        class="row"
        :class="[ editing ? 'justify-content-end' : '' ]"
    >
        <div 
            v-if="editing"
            class="my-4"
            :class="editStatus ? 'col-12' : 'col-10 bg-light p-3 rounded'"
        >
            <form>
                <vue-editor 
                    v-model="form.content"
                ></vue-editor>

                <p
                    v-if="errors.content"
                    v-text="errors.content[0]"
                    class="small text-danger mt-2"
                ></p>

                <div 
                    class="d-flex my-2"
                    v-if="!editStatus && editing"
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
        update () {
            axios.patch(`/parts/${this.part.id}/content`, this.form)
                .then(({data}) => {
                    this.part = data

                    this.cancel()
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        cancel () {
            this.editing = false
            
            this.form.content = this.part.data.content

            window.events.$emit('part:edit-cancel')
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