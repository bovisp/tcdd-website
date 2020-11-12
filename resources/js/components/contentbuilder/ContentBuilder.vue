<template>
    <div class="p-4 border rounded">
        <div class="flex">
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
            />
        </draggable>

        <add-part
            v-if="editing"
            :edit-status="editing"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Draggable from 'vuedraggable'

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
            editing: false
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
            window.events.$emit('series:edit')
        },

        async contentIds () {
            let { data } = await axios.get(`/api/content-builder/${this.contentIds[this.lang]}`)

            console.log(data)

            this.parts = data.data.parts
        }
    },

    methods: {
        update (e) {
            map(this.parts, (part, index) => part.sort_order = index + 1)

            axios.patch(`/api/content-builder/${this.contentIds[this.lang]}/change-order`, {
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
        window.events.$on('add-part:cancel', () => this.addPart = false)

        window.events.$on('add-part:reload', parts => {
            this.parts = parts
        })

        window.events.$on('part:deleted', partId => {
            this.parts = filter(this.parts, part => part.id !== partId)
        })

        window.events.$on('part:created', part => {
            this.parts.push(part)
        })
    }
}
</script>