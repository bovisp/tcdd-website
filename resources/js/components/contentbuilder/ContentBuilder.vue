<template>
    <div class="p-4 border rounded">
        <div class="flex items-center">
            <strong>
                {{ lang === 'en' ? 'English' : 'French' }}
            </strong>

            <button 
                class="btn btn-sm text-sm ml-auto"
                :class="editingClasses"
                @click.prevent="editing = !editing"
            >
                {{ trans('js_components_contentbuilder_contentbuilder.turnediting') }} {{ editing ? trans('js_components_contentbuilder_contentbuilder.off') : trans('js_components_contentbuilder_contentbuilder.on') }}
            </button>
        </div>

        <draggable
            :list="parts"
            handle='.fa-arrows-alt'
            @start="drag = true"
            @end="drag = false"
            @change="update"
        >
            <part-show 
                v-for="part in parts"
                :key="part.id"
                :data="part"
                :editing-on="editing"
                :lang="lang"
            />
        </draggable>

        <add-part
            v-if="editing"
            :edit-status="editing"
            :lang="lang"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Draggable from 'vuedraggable'
import { map, filter } from 'lodash-es'

export default {
    props: {
        lang: {
            type: String,
            required: true
        }
    },

    components: {
        Draggable
    },

    data () {
        return {
            editing: false,
            parts: [],
            form: {},
            addPart: false,
            editing: false,
            contentBuilderId: null
        }
    },

    computed: {
        ...mapGetters({
            questionId: 'questions/tempId',
            contentIds: 'questions/contentIds'
        }),

        editingClasses () {
            return this.editing ? 'btn-red' : 'btn-blue'
        }
    },

    watch: {
        editing () {
            window.events.$emit('series:edit', this.contentBuilderId)
        },

        async contentIds () {
            let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentIds[this.lang]}`)

            this.parts = data.data.parts

            this.contentBuilderId = data.data.id
        }
    },

    methods: {
        update (e) {
            map(this.parts, (part, index) => part.sort_order = index + 1)

            axios.patch(`${this.urlBase}/api/content-builder/${this.contentIds[this.lang]}/change-order`, {
                parts: map(this.parts, part => {
                    return {
                        id: part.id,
                        sort_order: part.sort_order
                    }
                })
            }).then(({data}) => {
                this.parts = data.data.parts
            })
        }
    },

    async mounted () {
        window.events.$on('add-part:cancel', contentBuilderId => {
            if (contentBuilderId === this.contentBuilderId) {
                this.addPart = false
            }
        })

        window.events.$on('add-part:reload', parts => {
            this.parts = parts
        })

        window.events.$on('part:deleted', payload => {
            if (payload.contentBuilderId === this.contentBuilderId) {
                this.parts = filter(this.parts, part => part.id !== payload.part)
            }
        })

        window.events.$on('part:created', payload => {
            if (payload.contentBuilderId === this.contentBuilderId) {
                this.parts.push(payload.data)
            }
        })
    }
}
</script>