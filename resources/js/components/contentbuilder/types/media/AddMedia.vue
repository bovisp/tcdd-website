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
            :file="form.filename"
            :adding="adding"
        />

        <b-field class="mt-2">
            <b-input 
                placeholder="Add an optional caption..."
                class="borderless-input"
                v-model="form.caption"
            ></b-input>
        </b-field>

        <store-buttons 
            @store="store('media')"
            @cancel="cancel"
        />
    </div>
</template>

<script>
import storeContentBuilder from '../../../../mixins/storeContentBuilder'

export default {
    mixins: [
        storeContentBuilder
    ],

    data () {
        return {
            adding: true,
            form: {
                content_builder_type_id: 4,
                filename: [],
                title: '',
                caption: '',
                is_tab_section: this.isTabSectionPart
            },
        }
    },

    methods: {
        async cancel () {
            await this.removeFile()

            this.adding = false

            this.genericCancel()
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
            if (this.adding) {
                this.form.filename.push({
                    file: file['file'],
                    original: file['original']
                })
            }
        })

        window.events.$on('media:remove', file => {
            if (this.adding && this.form.filename[0].file === file) {
                this.form.filename = []
            }
        })
    }
}
</script>