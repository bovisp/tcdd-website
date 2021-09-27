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

            <component 
                :is="`Add${ucfirst(type)}`"
                :edit-status="editStatus"
                :create-button-text="trans('generic.create')"
                :lang="lang"
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
import ucfirst from '../../helpers/ucfirst'
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
            contentIds: 'contentIds'
        }),

        showAddButton () {
            return !this.addingPart && this.showButton
        }
    },

    methods: {
        ucfirst,

        add (type) {
            this.type = type

            this.addingPart = false

            this.showButton = false
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
        window.events.$on('add-part:cancel', () => {
            this.showButton = true

            this.type = ''
        })

        window.events.$on('part:created', () => this.cancel())
    }
}
</script>