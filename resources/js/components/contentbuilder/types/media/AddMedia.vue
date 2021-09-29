<template>
    <div>
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
import { isEmpty } from 'lodash-es'
import storeContentBuilder from '../../../../mixins/storeContentBuilder'

export default {
    mixins: [
        storeContentBuilder
    ],

    data () {
        return {
            form: {
                content_builder_type_id: 4,
                filename: [],
                title: '',
                caption: '',
                is_tab_section: this.isTabSectionPart
            },
        }
    },

    watch: {
        'form.filename' () {
            if (!isEmpty(this.form.filename)) {
                console.log(this.form.filename)
            }
        }
    },

    methods: {
        async cancel () {
            await axios.delete(`${this.urlBase}/uploads`, {
                data: {
                    files: this.form.filename
                }
            })

            this.genericCancel()
        }
    },

    mounted () {
        window.events.$on('uploads:file', file => {
            this.form.filename.push({
                file: file['file'],
                original: file['original']
            })
        })
    }
}
</script>