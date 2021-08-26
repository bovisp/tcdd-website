<template>
    <div>
        <b-button
            type="is-info"
            v-if="showAddButton"
            size="is-small"
            expanded
            @click.prevent="addPartModal"
        >
            {{ trans('generic.addpart') }}
        </b-button>
        
        <div 
            v-if="type && !addingPart"
        >
            <hr class="my-8">

            <h4 class="subtitle is-4">
                 {{ trans('js_components_contentbuilder_addpart.new') }} {{ ucfirst(type) }} {{ trans('generic.part') }}
            </h4>

            <component 
                :is="`Add${ucfirst(type)}`"
                :edit-status="editStatus"
                :create-button-text="trans('generic.create')"
                :lang="lang"
            ></component>
        </div>

        <b-modal
            v-model="addingPart"
        >
            <div class="modal-card" style="width: auto">
                <header class="modal-card-head">
                    <p class="modal-card-title">
                        {{ trans('generic.addpart') }}
                    </p>

                    <button
                        type="button"
                        class="delete"
                        @click="cancel"/>
                </header>

                <section class="modal-card-body">
                    <div class="flex mb-4">
                        <div class="w-4/12 border border-t-0 border-b-0 border-l-0">
                            <form>
                                <div
                                    class="mb-2"
                                    v-for="t in types"
                                    :key="t.id"
                                >
                                    <b-radio 
                                        v-model="type"
                                        name="name"
                                        :native-value="t.type"
                                        @dblclick="add"
                                    >
                                        {{ ucfirst(t.type) }}
                                    </b-radio>
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
                </section>

                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click.prevent="cancel" />
                    <b-button
                        label="Add"
                        type="is-primary"
                        @click.prevent="add" />
                </footer>
            </div>
        </b-modal>
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
        let { data: types } = await axios.get(`${this.urlBase}/api/parts/types`)

        this.types = types.data

        window.events.$on('add-part:cancel', () => {
            this.showButton = true

            this.type = ''
        })
    }
}
</script>