<template>
    <div :class="[ editingTurnedOn ? 'row' : '' ]" class="my-4">
        <div 
            :class="[ editingTurnedOn && !editing ? 'col-2 d-flex' : 'd-none' ]"
        >
            <i class="fas fa-arrows-alt ml-auto" style="cursor: move"></i>

            <i 
                class="fas fa-edit ml-2"
                @click.prevent="edit"
            ></i>

            <i 
                class="fas fa-trash-alt text-danger ml-2"
                @click="showModal = true"
            ></i>
        </div>
        
        <div
            :class="[ editingTurnedOn && !editing ? 'col-10' : 'col-12' ]"
            v-if="typeof part !== 'undefined' && typeof part.builderType !== 'undefined'" 
        >
            <component 
                :is="`Show${formatType}`"
                :series-id="1"
                :data="part"
            ></component>
        </div>

        <modal 
            v-if="showModal" 
            @close="destroy"
            @cancel="showModal = false"
        >
            <h3 slot="header">Delete part</h3>

            <p slot="body">
                Are you ure you want to do this? Like, really sure?
            </p>

            <div slot="footer" class="d-flex justify-content-end">
                <button 
                    class="btn btn-text mr-2" 
                    @click="showModal = false"
                >Cancel</button>

                <button 
                    class="btn btn-primary" 
                    @click="destroy"
                >OK</button>
            </div>
        </modal>
    </div>
</template>

<script>
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
        formatType () {
            return this.part.builderType.type.charAt(0).toUpperCase() + this.part.builderType.type.slice(1)
        }
    },

    methods: {
        destroy () {
            axios.delete(`/parts/${this.part.id}`, {
                data: {
                    type: this.part.builderType.type
                }
            })
                .then(response => {
                    this.showModal = false

                    window.events.$emit('part:deleted', this.part.id)
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
        },

        edit () {
            this.editing = true

            window.events.$emit('part:edit', this.part.id)
        }
    },

    mounted () {
        this.part = this.data

        window.events.$on('series:edit', () => this.editingTurnedOn = !this.editingTurnedOn)

        window.events.$on('part:edit-cancel', () => {
            this.editing = false
            this.editingTurnedOn = true
        })

        this.editingTurnedOn = this.editingOn
    }
}
</script>