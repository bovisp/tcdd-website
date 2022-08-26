<template>
    <div>
        <b-field>
            <b-input 
                :placeholder="trans('js_components_contentbuilder_generic.addoptionaltitle')"
                size="is-medium"
                class="borderless-input borderless-input-md"
                v-model="form.title"
            ></b-input>
        </b-field>

        <div v-if="form.files.length && (animationSaved || !isEmpty(data))">
            <image-animator 
                :files="form.files"
                :fluid="true"
            />
        </div>

        <div 
            class="card"
            v-else
        >
            <div class="card-content">
                <div 
                    class="level"
                    v-if="form.files.length"
                >
                    <div class="level-left"></div>

                    <div class="level-right">
                        <div class="level-item">
                            <b-button
                                type="is-text"
                                class="is-text-info"
                                @click.prevent="animationSaved = true"
                            >{{ trans('js_components_contentbuilder_types_animation.saveanimation') }}</b-button>
                        </div>
                    </div>
                </div>

                <uploader 
                    :options="{
                        baseURL: '',
                        maxConcurrentUploads: 30,
                        maxFiles: 30
                    }"

                    :handlers="{
                        'image/gif': {
                            endpoint: '/uploads?type=image'
                        },
                        'image/jpeg': {
                            endpoint: '/uploads?type=image'
                        },
                        'image/png': {
                            endpoint: '/uploads?type=image'
                        }
                    }"
                />

                <div 
                    class="level"
                    v-if="form.files.length"
                >
                    <div class="level-left"></div>
                    
                    <div class="level-right">
                        <div class="level-item">
                            <b-button
                                type="is-text"
                                class="has-text-info"
                                @click.prevent="animationSaved = true"
                            >{{ trans('js_components_contentbuilder_types_animation.saveanimation') }}n</b-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-field class="mt-2">
            <b-input 
                :placeholder="trans('js_components_contentbuilder_generic.addoptionalcaption')"
                class="borderless-input"
                v-model="form.caption"
            ></b-input>
        </b-field>
    </div>
</template>

<script>
import contentBuilderData from '../../../../../mixins/contentBuilder'
import { isEmpty, merge } from 'lodash-es'
import { mapActions } from 'vuex'

export default {
    mixins: [
        contentBuilderData
    ],

    props: {
        data: {
            type: Object,
            required: false
        },
        isTabSectionPart: {
            type: Boolean,
            required: false,
            default: false
        },
        tabPartDataId: {
            type: Number,
            required: false,
            default: null
        }
    },

    data () {
        return {
            form: {
                files: [],
                title: '',
                caption: ''
            },
            animationSaved: false,
            order: 1
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
                        tabPartDataId: this.tabPartDataId,
                        isTabSectionPart: this.isTabSectionPart,
                        payload: {
                            files: this.form.files,
                            title: this.form.title,
                            caption: this.form.caption
                        }
                    })
                } else {
                    this.updateEditForm({
                        currentContentBuilder: this.currentContentBuilder,
                        partDataId: this.data.data.id,
                        tabPartDataId: this.tabPartDataId,
                        type: this.data.builderType.type,
                        payload: {
                            files: this.form.files,
                            title: this.form.title,
                            caption: this.form.caption
                        }
                    })
                }
            }
        },
    },

    methods: {
        ...mapActions({
            updateNewForm: 'contentbuilder/updateNewForm',
            updateEditForm: 'contentbuilder/updateEditForm',
            removeFile: 'contentbuilder/removeFile'
        }),

        isEmpty
    },

    mounted () {
        if (!isEmpty(this.data)) {
            this.form.files = this.data.data.images

            this.form.title = this.data.data.title

            this.form.caption = this.data.data.caption
        }

        window.events.$on('media:remove', async file => {
            if (typeof this.form.files !== 'undefined') {
                if (this.form.files[0].file === file) {
                    await this.removeFile(this.form.files)
                    
                    this.form.files = []
                }
            }
        })

        window.events.$on('uploads:file', file => {
            this.form.files.push(merge(file, { order: this.order}))

            this.order += 1
        })
    }
}
</script>