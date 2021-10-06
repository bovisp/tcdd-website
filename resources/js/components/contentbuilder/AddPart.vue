<template>
    <div id="add-part-section">
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

            <component 
                :is="`Add${pascalCase(type)}`"
                :create-button-text="trans('generic.create')"
                :id="currentContentBuilder.id"
                :type="type"
            ></component>
        </div>

        <add-part-modal 
            v-if="addingPart"
            @cancel="cancel"
            @add="add"
        />
    </div>
</template>

<script>
import { pascalCase } from 'change-case'
import VueScrollTo from 'vue-scrollto'
import contentBuilderData from '../../mixins/contentBuilder'

export default {
    mixins: [
        contentBuilderData
    ],

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

    methods: {
        pascalCase,

        add (type) {
            this.type = type

            this.addingPart = false

            this.showButton = false

            VueScrollTo.scrollTo('#add-part-section')
        },

        addPartModal () {
            this.addingPart = true

            this.showButton = false
        },

        cancel () {
            this.type = ''

            this.addingPart = false

            this.showButton = true
        }
    },

    async mounted () {
        window.events.$on('part:add-cancel', () => {
            this.showButton = true

            this.type = ''
        })

        // window.events.$on('part:created', () => this.cancel())
    }
}
</script>