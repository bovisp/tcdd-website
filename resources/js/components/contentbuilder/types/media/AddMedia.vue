<template>
    <div class="w-full">
        <b-field>
            <b-input 
                placeholder="Add an optional title..."
                size="is-medium"
                class="borderless-input borderless-input-md"
                v-model="form.title"
            ></b-input>
        </b-field>

        <edit-media 
            :id="currentContentBuilder.id"
            :is-tab-section-part="isTabSectionPart"
            :tab-part-data-id="tabPartDataId"
        />

        <b-field class="mt-2">
            <b-input 
                placeholder="Add an optional caption..."
                class="borderless-input"
                v-model="form.caption"
            ></b-input>
        </b-field>

        <store-buttons 
             @store="store({
                type: 'media',
                id: currentContentBuilder.id,
                isTabSectionPart,
                tabPartDataId
            })"
            @cancel="cancel"
        />
    </div>
</template>

<script>
import storeContentBuilder from '../../../../mixins/storeContentBuilder'
import { mapActions } from 'vuex'
import { forIn } from 'lodash-es'

export default {
    mixins: [
        storeContentBuilder
    ],

    data () {
        return {
            form: {
                title: '',
                caption: ''
            }
        }
    },

    watch: {
        form: {
            deep: true,

            handler () {
                this.updateNewForm({
                    currentContentBuilder: this.currentContentBuilder,
                    partial: true,
                    payload: {
                        title: this.form.title,
                        caption: this.form.caption
                    }
                })
            }
        },

        errors: {
            deep: true,

            handler () {
                forIn(this.errors, (value, key) => {
                    if (key.includes('filename')) {
                        this.$buefy.dialog.alert({
                            title: 'Error',
                            message: 'You need to add a file first.',
                            type: 'is-danger',
                            ariaRole: 'alertdialog',
                            ariaModal: true
                        })
                    }
                })
            }
        }
    },

    methods: {
        ...mapActions({
            removeFile: 'contentbuilder/removeFile',
            updateNewForm: 'contentbuilder/updateNewForm'
        }),

        store (payload) {
            this.createPart(payload)
        },

        async cancel () {
            await this.removeFile(this.currentContentBuilder.new.filename)

            this.genericCancel()

            window.events.$emit('tabs:cancel-add-part')
        }
    }
}
</script>