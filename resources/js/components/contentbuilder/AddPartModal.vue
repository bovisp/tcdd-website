<template>
    <b-modal
        v-model="addingPart"
        @close="$emit('cancel')"
    >
        <div class="modal-card" style="width: auto">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ trans('generic.addpart') }}
                </p>

                <button
                    type="button"
                    class="delete"
                    @click="$emit('cancel')"/>
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
                                    @dblclick="$emit('add', type)"
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
                    @click.prevent="$emit('cancel')" />
                <b-button
                    label="Add"
                    type="is-primary"
                    @click.prevent="$emit('add', type)" />
            </footer>
        </div>
    </b-modal>
</template>

<script>
import { find, filter } from 'lodash-es'
import ucfirst from '../../helpers/ucfirst'

export default {
    props: {
        omitType: {
            type: String,
            required: false,
            default: ''
        }
    },

    data () {
        return {
            addingPart: true,
            type: '',
            description: '',
            types: []
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
        ucfirst
    },

    async mounted () {
        let { data: types } = await axios.get(`${this.urlBase}/api/parts/types`)

        this.types = filter(types.data, type => type.type !== this.omitType)
    }
}
</script>