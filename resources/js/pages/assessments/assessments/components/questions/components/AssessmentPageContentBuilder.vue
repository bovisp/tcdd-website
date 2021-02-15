<template>
    <div class="p-4 border rounded mb-2">
        <div class="flex items-center">
            <strong>
                {{ lang === 'en' ? 'English' : 'French' }}
            </strong>

            <button 
                class="btn btn-sm text-sm ml-auto"
                :class="editingClasses"
                @click.prevent="editing = !editing"
            >
                Turn editing {{ editing ? 'off' : 'on' }}
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
                :content-builder-id="contentBuilderId"
            />
        </draggable>

        <assessment-page-content-builder-add-part
            v-if="editing"
            :edit-status="editing"
            :lang="lang"
            :content-builder-id="contentBuilderId"
        />
    </div>
</template>

<script>
import Draggable from 'vuedraggable'
import { map, filter } from 'lodash-es'

export default {
    components: {
        Draggable
    },

    props: {
        contentBuilderId: {
            type: Number,
            required: true
        },
        lang: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            parts: [],
            editing: false,
            form: {},
            addPart: false,
            editing: false,
        }
    },

    computed: {
        editingClasses () {
            return this.editing ? 'btn-red' : 'btn-blue'
        }
    },

    methods: {
        update (e) {
            map(this.parts, (part, index) => part.sort_order = index + 1)

            axios.patch(`${this.urlBase}/api/content-builder/${this.contentBuilderId}/change-order`, {
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
        let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentBuilderId}`)

        this.parts = data.data.parts

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