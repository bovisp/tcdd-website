<template>
    <div>
        <b-field
            v-if="!displaySaveButton"
        >
            <b-input 
                placeholder="Add an optional tab title..."
                size="is-medium"
                class="borderless-input borderless-input-md"
                v-model="form.title"
            ></b-input>
        </b-field>

        <div 
            class="flex"
            v-if="!displaySaveButton || (!isEmpty(data) && data.editingPart && !displaySaveButton)"
        >
            <b-button
                type="is-text"
                size="is-small"
                class="ml-auto"
                @click.prevent="toggleSaveButton"
            >Edit tabs</b-button>
        </div>

        <tab-list 
            :id="currentContentBuilder.id"
            :data="data"
            :show-add-tab-button="displaySaveButton"
        />

        <b-field
            class="mt-3 mb-5"
        >
            <b-button
                type="is-info"
                size="is-small"
                expanded
                @click.prevent="toggleSaveButton"
                v-if="displaySaveButton"
            >Save tabs</b-button>
        </b-field>

        <b-field
            v-if="!displaySaveButton"
            class="mb-3"
        >
            <b-input 
                placeholder="Add an optional tab caption..."
                class="borderless-input"
                v-model="form.caption"
            ></b-input>
        </b-field>
    </div>
</template>

<script>
import contentBuilderData from '../../../../../mixins/contentBuilder'
import { isEmpty } from 'lodash-es'
import { mapActions } from 'vuex'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        data: {
            type: Object,
            required: false
        }
    },

    data () {
        return {
            form: {
                title: '',
                caption: ''
            },
            displaySaveButton: true
        }
    },

    watch: {
        form: {
            deep: true,

            handler () {
                if (isEmpty(this.data)) {
                    this.updateNewForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partial: true,
                        payload: {
                            caption: this.form.caption,
                            title: this.form.title
                        }
                    })
                } else {
                    this.updateEditForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partial: true,
                        partDataId: this.data.data.id,
                        type: this.data.builderType.type,
                        payload: {
                            caption: this.form.caption,
                            title: this.form.title
                        }
                    })
                }
            }
        }
    },
    
    methods: {
        ...mapActions({
            updateNewForm: 'contentbuilder/updateNewForm',
            updateEditForm: 'contentbuilder/updateEditForm'
        }),

        isEmpty,

        toggleSaveButton () {
            this.displaySaveButton = !this.displaySaveButton

            this.$emit('tabs:toggle-save-button')

            this.$emit('tabs:toggle-update-button')
        }
    },

    mounted () {
        if (!isEmpty(this.data)) {
            this.form.caption = this.data.data.caption

            this.form.title = this.data.data.title
        }
    }
}
</script>