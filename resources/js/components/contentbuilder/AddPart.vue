<template>
    <div>
        <button 
            class="btn btn-blue btn-sm text-sm w-full mt-6"
            v-if="showAddButton"
            @click.prevent="addPartModal"
        >
            {{ trans('js_components_contentbuilder_addpart.addpart') }}
        </button>

        <div 
            v-if="type && !addingPart"
        >
            <hr class="my-8">

            <h4 class="w-full font-light text-center mb-6 text-2xl">
                 {{ trans('js_components_contentbuilder_addpart.new') }} {{ ucfirst(type) }} {{ trans('js_components_contentbuilder_addpart.part') }}
            </h4>

            <component 
                :is="`Add${ucfirst(type)}`"
                :edit-status="editStatus"
                :lang="lang"
            ></component>
        </div>

        <modal 
            v-if="addingPart" 
            @submit="add"
            @close="cancel"
            ok-button-text="Submit"
            cancel-button-text="Cancel"
            okButtonText="trans('js_components_contentbuilder_addpart.create')"
        >
            <h3 slot="header" class="mb-4">
                {{ trans('js_components_contentbuilder_addpart.addpart') }}
            </h3>

            <div slot="body">
                <div class="flex mb-4">
                    <div class="w-4/12 border border-t-0 border-b-0 border-l-0">
                        <form>
                            <div
                                class="mb-2"
                                v-for="t in types"
                                :key="t.id"
                            >
                                <label
                                    :for="t.type"
                                    @dblclick="add"
                                >
                                    <input 
                                        type="radio" 
                                        class="form-radio" 
                                        :id="t.type" 
                                        :value="t.type"
                                        v-model="type"
                                        @dblclick="add"
                                    >

                                    <span class="ml-2">{{ ucfirst(t.type) }}</span>
                                </label>
                            </div>
                        </form>
                    </div>

                    <div class="w-8/12 px-4">
                        <p v-if="!description">
                            {{ trans('js_components_contentbuilder_addpart.pleaseselecttype') }}
                        </p>

                        <div 
                            v-else
                            v-html="description"
                            class="content overflow-auto"
                        ></div>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import ucfirst from '../../helpers/ucfirst'
import { find } from 'lodash-es'
import { mapGetters } from 'vuex'

export default {
    props: {
        editStatus: {
            type: Boolean,
            required: true
        },
        lang: {
            type: String,
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
        ...mapGetters({
            contentIds: 'questions/contentIds'
        }),

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
        this.type = ''
        
        let { data: types } = await axios.get(`${this.urlBase}/api/parts/types`)

        this.types = types.data

        window.events.$on('add-part:cancel', () => {
            this.showButton = true

            this.type = ''
        })
    }
}
</script>