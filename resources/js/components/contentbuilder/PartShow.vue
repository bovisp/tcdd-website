<template>
    <div class="flex my-4">
        <div 
            :class="[ editingTurnedOn && !editing ? 'w-2/12 flex items-start' : 'hidden' ]"
        >
            <b-icon 
                class="ml-auto cursor-move mt-3"
                icon="arrow-all"
            ></b-icon>

            <b-button
                icon-right="pencil"
                type="is-text"
                size="is-medium"
                :title="`${trans('generic.edit')} ${noCase(trans('generic.content'))}`"
                @click.prevent="edit"
            ></b-button>

            <b-button
                icon-right="delete"
                type="is-text"
                size="is-medium"
                :title="`${trans('generic.delete')} ${trans('generic.content')}`"
                class="has-text-danger"
                @click.prevent="showModal = true" 
            ></b-button>
        </div>
        
        <div
            :class="[ editingTurnedOn && !editing ? 'w-10/12 pl-4' : 'w-full' ]"
            v-if="typeof part !== 'undefined' && typeof part.builderType !== 'undefined'" 
        >
            <component 
                :is="`Show${formatType}`"
                :content-builder-id="builderId"
                :data="part"
                :lang="lang"
            ></component>
        </div>

        <modal 
            v-if="showModal" 
            cancel-button-text="Cancel"
            @submit="destroy"
            ok-button-text="Delete"
            @close="showModal = false"
        >
            <h3 slot="header" class="mb-4">
                {{ trans('js_components_contentbuilder_partpart.deletepart') }}
            </h3>

            <p slot="body" class="mb-4">
                {{ trans('js_components_contentbuilder_partpart.areyousure') }}
            </p>
        </modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { noCase } from 'change-case'

export default {
    props: {
        data: {
            type: Object,
            required: true
        },
        editingOn: {
            type: Boolean,
            required: false,
            default: false
        },
        lang: {
            type: String,
            required: true
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        }
    },

    data () {
        return {
            editing: false,
            editingTurnedOn: false,
            form: {
                content: ''
            },
            part: {},
            showModal: false,
            builderId: null
        }
    },

    watch: {
        editingTurnedOn () {
            if (this.editingTurnedOn === false) {
                this.editing = false
            }
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'questions/contentIds'
        }),

        formatType () {
            return this.part.builderType.type.charAt(0).toUpperCase() + this.part.builderType.type.slice(1)
        }
    },

    methods: {
        noCase,

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/parts/${this.part.id}`, {
                data: {
                    type: this.part.builderType.type
                }
            })

            this.showModal = false

            window.events.$emit('part:deleted', {
                part: this.part.id,
                contentBuilderId: this.builderId
            })
        },

        edit () {
            this.editing = true

            window.events.$emit('part:edit', this.part.id)
        }
    },

    mounted () {
        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]

        this.part = this.data

        window.events.$on('series:edit', contentBuilderId => {
            if (contentBuilderId === this.builderId) {
                this.editingTurnedOn = !this.editingTurnedOn
            }
        })

        window.events.$on('part:edit-cancel', partId => {
            if (partId === this.part.id) {
                this.editing = false
                this.editingTurnedOn = true
            }
        })

        this.editingTurnedOn = this.editingOn
    }
}
</script>