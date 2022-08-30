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
                        {{ editing ? trans('js_components_contentbuilder_contentbuilder.turneditingoff') : trans('js_components_contentbuilder_contentbuilder.turneditingon') }}
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

            <template v-if="editing">
                <hr class="my-4">

                <add-part
                    :id="currentContentBuilder.id"
                />
            </template>
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
        console.log(this.id)
        await this.setContentBuilder(this.id)
    }
}
</script>