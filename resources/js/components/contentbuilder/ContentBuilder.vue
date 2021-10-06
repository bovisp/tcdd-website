<template>
    <div 
        class="card"
        v-if="currentContentBuilder"
    >
        <div class="card-content">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                         <strong>
                            {{ currentContentBuilder.lang === 'en' ? trans('generic.english') : trans('generic.french') }}
                        </strong>
                    </div>
                </div>

                <div class="level-right">
                    <b-button
                        :type="editing ? 'is-danger' : 'is-info'"
                        @click.prevent="updateEditStatus(id)"
                        size="is-small"
                    >
                        {{ trans('js_components_contentbuilder_contentbuilder.turnediting') }} {{ editing ? trans('js_components_contentbuilder_contentbuilder.off') : trans('js_components_contentbuilder_contentbuilder.on') }}
                    </b-button>
                </div>
            </div>

            <draggable
                :list="orderedParts"
                handle='.mdi-arrow-all'
                @start="drag = true"
                @end="drag = false"
                @change="changeOrder"
            >
                <part-show 
                    v-for="part in orderedParts"
                    :key="part.id"
                    :data="part"
                    :id="currentContentBuilder.id"
                />
            </draggable>

            <add-part
                v-if="editing"
                :id="currentContentBuilder.id"
            />
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'
import Draggable from 'vuedraggable'
import contentBuilderData from '../../mixins/contentBuilder'

export default {
    mixins: [
        contentBuilderData
    ],

    components: {
        Draggable
    },

    data () {
        return {
            addPart: false
        }
    },

    methods: {
        ...mapActions({
            setContentBuilder: 'contentbuilder/setContentBuilder',
            updateEditStatus: 'contentbuilder/updateEditStatus',
            changePartOrder: 'contentbuilder/changePartOrder'
        }),

        changeOrder(event) {
            this.changePartOrder({
                id: this.currentContentBuilder.id,
                event
            })
        }
    },

    async mounted () {
        await this.setContentBuilder(this.id)
    }
}

//     methods: {
//         update (e) {
//             map(this.parts, (part, index) => part.sort_order = index + 1)

//             axios.patch(`${this.urlBase}/api/content-builder/${this.contentIds[this.lang]}/change-order`, {
//                 parts: map(this.parts, part => {
//                     return {
//                         id: part.id,
//                         sort_order: part.sort_order
//                     }
//                 })
//             }).then(({data}) => {
//                 this.parts = data.data.parts
//             })
//         }
//     },

//     async mounted () {
//         window.events.$on('add-part:cancel', contentBuilderId => {
//             if (contentBuilderId === this.contentBuilderId) {
//                 this.addPart = false
//             }
//         })

//         window.events.$on('add-part:reload', parts => {
//             this.parts = parts
//         })

//         window.events.$on('part:deleted', payload => {
//             if (payload.contentBuilderId === this.contentBuilderId) {
//                 this.parts = filter(this.parts, part => part.id !== payload.part)
//             }
//         })

//         window.events.$on('part:created', payload => {
//             if (payload.contentBuilderId === this.contentBuilderId) {
//                 this.parts.push(payload.data)
//             }
//         })
//     }
// }
</script>