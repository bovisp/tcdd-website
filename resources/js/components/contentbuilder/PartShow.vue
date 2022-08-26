<template>
    <div 
        class="flex my-4"
        :class="[ data.editingPart ? 'justify-end' : '' ]"
        :id="`part-${data.id}`"
    >
        <div 
            :class="[ editing && !data.editingPart ? 'w-3/12 flex flex-col 2xl:flex-row items-end 2xl:items-start' : 'hidden' ]"
        >
            <b-icon 
                class="ml-auto cursor-move mt-3 mr-2"
                icon="arrow-all"
            ></b-icon>

            <b-button
                icon-right="pencil"
                type="is-text"
                size="is-medium"
                :title="`${trans('generic.edit')} ${noCase(trans('generic.content'))}`"
                @click.prevent="edit({
                    id,
                    partId: data.id
                })"
            ></b-button>

            <b-button
                icon-right="delete"
                type="is-text"
                size="is-medium"
                :title="`${trans('generic.delete')} ${trans('generic.content')}`"
                class="has-text-danger"
                @click.prevent="showModal = true" 
            ></b-button>
        </div>
        
        <div
            :class="[ !editing ? 'w-full' : 'w-9/12' ]"
            v-if="typeof data !== 'undefined' && typeof data.builderType !== 'undefined'" 
        >
            <component 
                :is="`Show${pascalCase(data.builderType.type)}`"
                :id="id"
                :data="data"
            ></component>
        </div>

        <modal 
            v-if="showModal" 
            :cancel-button-text="trans('generic.delete')"
            @submit="destroy({
                partId: data.id,
                type: data.builderType.type,
                id
            })"
            :ok-button-text="trans('generic.delete')"
            @close="showModal = false"
        >
            <h3 slot="header" class="mb-4">
                {{ trans('js_components_contentbuilder_partpart.deletepart') }}
            </h3>

            <p slot="body" class="mb-4">
                {{ trans('js_components_contentbuilder_partpart.areyousure') }}
            </p>
        </modal>
    </div>
</template>

<script>
import { noCase, pascalCase } from 'change-case'
import contentBuilderData from '../../mixins/contentBuilder'
import { mapActions } from 'vuex'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        data: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            showModal: false
        }
    },

    methods: {
        ...mapActions({
            edit: 'contentbuilder/editPart',
            destroy: 'contentbuilder/destroyPart'
        }),

        noCase,

        pascalCase
    },

    mounted () {
        window.events.$on('part:hide-delete-part-confirm', () => this.showModal = false)
    }
}
</script>