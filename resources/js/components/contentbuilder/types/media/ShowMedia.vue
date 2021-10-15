<template>
    <div v-if="!isEmpty(data)">
        <template v-if="!data.editingPart && !isEmpty(data)">
            <component 
                :is="`Final${ pascalCase(data.builderType.type) }`"
                :data="data"
                :id="id"
            ></component>
        </template>

        <div v-if="data.editingPart">
            <form>
                <b-field>
                    <b-input 
                        placeholder="Add an optional title..."
                        size="is-medium"
                        class="borderless-input borderless-input-md"
                        v-model="form.title"
                    ></b-input>
                </b-field>

                <edit-media 
                    :data="data"
                    :id="id"
                />

                <b-field class="mt-2">
                    <b-input 
                        placeholder="Add an optional caption..."
                        class="borderless-input"
                        v-model="form.caption"
                    ></b-input>
                </b-field>

                <div class="mt-2">
                    <update-buttons 
                         @update="update({
                            type: 'media',
                            id: currentContentBuilder.id,
                            partDataId: data.data.id,
                            partId: data.id
                        })"
                        @cancel="cancel"
                    />
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import updateContentBuilder from '../../../../mixins/updateContentBuilder'
import { mapActions } from 'vuex'
import { isEmpty, forIn } from 'lodash-es'

export default {
    mixins: [
        updateContentBuilder
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
                this.updateEditForm({
                    currentContentBuilder: this.currentContentBuilder,
                    partDataId: this.data.data.id,
                    type: this.data.builderType.type,
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
            updateEditForm: 'contentbuilder/updateEditForm'
        }),

        cancel () {
            this.cancelEditingPart({
                id: this.id,
                partId: this.data.id
            })
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