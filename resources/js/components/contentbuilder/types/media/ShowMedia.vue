<template>
    <div v-if="!isEmpty(part)">
        <template v-if="!editing && !isEmpty(part)">
            <component 
                :is="`Final${ pascalCase(part.builderType.type) }`"
                :part="part"
            ></component>
        </template>

        <div v-if="editing">
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
                    :file="form.filename"
                    :editing="editing"
                    :partId="part.data.id"
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
                        @update="update('media')"
                        @cancel="cancel"
                    />
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import updateContentBuilder from '../../../../mixins/updateContentBuilder'

export default {
    mixins: [
        updateContentBuilder
    ],

    data () {
        return {
            form: {
                title: '',
                caption: '',
                filename: []
            }
        }
    },

    methods: {
        setContent () {
            this.form.title = this.part.data.title

            this.form.caption = this.part.data.caption

            this.form.filename = this.part.data.filename
        },

        cancel () {
            this.editing = false

            window.events.$emit('part:edit-cancel')
        },

        async removeFile () {
            await axios.delete(`${this.urlBase}/uploads`, {
                data: {
                    files: this.form.filename
                }
            })

            this.form.filename = []
        }
    },

    mounted () {
        window.events.$on('uploads:file', file => {
            if (this.editing) {
                this.form.filename.push({
                    file: file['file'],
                    original: file['original']
                })

                window.events.$emit('media:replace', {
                    file,
                    partId: this.part.data.id
                })
            }
        })

        window.events.$on('media:remove', file => {
            if (this.editing && this.form.filename[0].file === file) {
                this.removeFile()
            }
        })
    }
}
</script>