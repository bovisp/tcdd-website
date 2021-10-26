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
                <edit-media 
                    :data="data"
                    :id="id"
                />

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
import { forIn } from 'lodash-es'

export default {
    mixins: [
        updateContentBuilder
    ],

    watch: {
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
    }
}
</script>