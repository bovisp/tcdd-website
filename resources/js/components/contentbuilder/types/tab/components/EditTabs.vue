<template>
    <div>
        <b-field>
            <b-input 
                placeholder="Add an optional tab title..."
                size="is-medium"
                class="borderless-input borderless-input-md"
                v-model="form.title"
            ></b-input>
        </b-field>

        <tab-list 
            :id="currentContentBuilder.id"
            :data="data"
        />

        <b-field>
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
            }
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
        })
    },

    mounted () {
        if (!isEmpty(this.data)) {
            this.form.caption = this.data.data.caption

            this.form.title = this.data.data.title
        }
    }
}
</script>