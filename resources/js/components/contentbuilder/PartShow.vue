<template>
    <div class="flex my-4">
        <div 
            :class="[ editingTurnedOn && !editing ? 'w-2/12 flex items-start' : 'hidden' ]"
        >
            <i class="fas fa-arrows-alt ml-auto cursor-move"></i>

            <i 
                class="fas fa-edit ml-2"
                @click.prevent="edit"
            ></i>

            <i 
                class="fas fa-trash-alt text-red-500 ml-2"
                @click="showModal = true"
            ></i>
        </div>
        
        <div
            :class="[ editingTurnedOn && !editing ? 'w-10/12 pl-4' : 'w-full' ]"
            v-if="typeof part !== 'undefined' && typeof part.builderType !== 'undefined'" 
        >
            <component 
                :is="`Show${formatType}`"
                :content-builder-id="contentIds[lang]"
                :data="part"
                :lang="lang"
            ></component>
        </div>

        <modal 
            v-if="showModal" 
            @submit="destroy"
            @close="showModal = false"
        >
            <h3 slot="header" class="mb-4">Delete part</h3>

            <p slot="body" class="mb-4">
                Are you ure you want to do this? Like, really sure?
            </p>
        </modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

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
            showModal: false
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
        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/parts/${this.part.id}`, {
                data: {
                    type: this.part.builderType.type
                }
            })

            this.showModal = false

            window.events.$emit('part:deleted', {
                part: this.part.id,
                contentBuilderId: this.contentIds[this.lang]
            })
        },

        edit () {
            this.editing = true

            window.events.$emit('part:edit', this.part.id)
        }
    },

    mounted () {
        this.part = this.data

        window.events.$on('series:edit', contentBuilderId => {
            if (contentBuilderId === this.contentIds[this.lang]) {
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