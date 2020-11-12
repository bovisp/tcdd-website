<template>
    <div>
        <button 
            class="btn btn-primary btn-block mt-4"
            v-if="showAddButton"
            @click.prevent="addPartModal"
        >
            Add part
        </button>

        <div 
            v-if="type && !addingPart"
        >
            <hr class="my-5">

            <h4 class="w-100 font-weight-light text-center mb-4">
                 New {{ ucfirst(type) }} Part
            </h4>

            <component 
                :is="`Add${ucfirst(type)}`"
                :series-id="1"
                :edit-status="editStatus"
            ></component>
        </div>

        <modal 
            v-if="addingPart" 
            @close="add"
            @cancel="addingPart = false"
            class="app-modal"
        >
            <h3 slot="header">Add part</h3>

            <div slot="body" style="height: 100%;">
                <div class="row">
                    <div class="col-md-4 border-right">
                        <form>
                            <div 
                                class="form-check mb-2"
                                v-for="t in types"
                                :key="t.id"
                            >
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    :id="t.type" 
                                    :value="t.type"
                                    v-model="type"
                                    @dblclick="add"
                                >

                                <label 
                                    class="form-check-label" 
                                    :for="t.type"
                                    @dblclick="add"
                                >
                                    {{ ucfirst(t.type) }}
                                </label>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-8">
                        <p v-if="!description">
                            Please select a type
                        </p>

                        <div 
                            v-else
                            v-html="description"
                            style="overflow: auto;"
                        ></div>
                    </div>
                </div>
            </div>

            <div slot="footer" class="d-flex justify-content-end">
                <button 
                    class="btn btn-text mr-2" 
                    @click="cancel"
                >Cancel</button>

                <button 
                    class="btn btn-primary" 
                    @click="add"
                >Create</button>
            </div>
        </modal>
    </div>
</template>

<script>
import ucfirst from '../../helpers/ucfirst'
import { find } from 'lodash-es'

export default {
    props: {
        editStatus: {
            type: Boolean,
            required: true
        }
    },

    data () {
        return {
            addingPart: false,
            showButton: true,
            type: '',
            description: '',
            types: []
        }
    },

    computed: {
        showAddButton () {
            return !this.addingPart && this.showButton
        }
    },

    watch: {
        type () {
            if (!this.type && this.addingPart) {
                return
            }
            
            let type = find(this.types, t => this.type === t.type)

            if (type) {
                this.description = type.description
            }
        }
    },

    methods: {
        ucfirst,

        add () {
            this.addingPart = false

            this.showButton = false
        },

        addPartModal () {
            this.addingPart = true

            this.showButton = false
        },

        cancel () {
            this.type = ''

            this.description = ''

            this.addingPart = false

            this.showButton = true
        }
    },

    async mounted () {
        let { data: types } = await axios.get('/api/parts/types')

        this.types = types.data

        window.events.$on('add-part:cancel', () => {
            this.showButton = true

            this.type = ''
        })
    }
}
</script>

<style>
    .form-check:last-child {
        margin-bottom: 0 !important;
    }

    .app-modal .modal-container {
        height: 70%;
    }

    .app-modal .modal-container .card {
        height: 100%;
    }

    .app-modal .modal-container .card .row {
        height: 100%;
        overflow: auto;
    }
</style>