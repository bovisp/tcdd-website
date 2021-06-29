<template>
    <div>
        <div
            class="mb-4"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
                :class="{ 'text-red-500': errors.length > 0 }"
                :for="`title-${section.id}`"
            >
                {{ trans('js_components_contentbuilder_types_tab_components_edittabsection.tabtitle') }}
            </label>

            <input 
                type="text" 
                v-model="form.title"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                :id="`title-${section.id}`"
                :class="{ 'border-red-500': errors.length > 0 }"
            >

            <p
                v-if="errors.length > 0"
                v-text="errors[0]"
                class="text-red-500 text-xs"
            ></p>
        </div>

        <div
            class="mb-4"
        >
            <h5 class="mt-6 -mb-4 text-2xl">
                {{ trans('js_components_contentbuilder_types_tab_components_edittabsection.tabcontent') }} ({{ section.type }})
            </h5>

            <component 
                :is="`Show${ucfirst(section.type)}`"
                :content-builder-id="contentIds[lang]"
                :edit-status="editStatus"
                :data="section.content"
                :id="section.content_id"
                @edited="addToForm"
            ></component>
        </div>

        <div
            class="mb-4 -mt-6"
        >
            <button 
                class="btn btn-sm text-sm btn-outline"
                @click="deleteTab"
            >
                {{ trans('js_components_contentbuilder_types_tab_components_edittabsection.deletetab') }}
            </button>
        </div>

        <hr class="my-6">
    </div>
</template>

<script>
import ucfirst from '../../../../../helpers/ucfirst'
import { merge } from 'lodash-es'
import { mapGetters } from 'vuex'

export default {
    props: {
        section: {
            type: Object,
            required: true
        },
        lang: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            editStatus: true,
            form: {
                title: '',
                data: {}
            }
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'questions/contentIds'
        })
    },

    watch: {
        'form.title' () {
            window.events.$emit('tab-content:edit-section', {
                id: this.section.id,
                type: 'title',
                data: this.form.title
            })
        },

        'form.data' () {
            window.events.$emit('tab-content:edit-section', {
                id: this.section.id,
                type: 'data',
                data: this.form.data
            })
        }
    },

    methods: {
        ucfirst,

        deleteTab () {
            window.events.$emit('tab-content:destroy', this.section.id)
        },

        addToForm(data) {
            this.form.data = merge(data, {
                type: this.section.type
            })
        }
    },


    mounted () {
        this.form.title = this.section.title

        window.events.$on('tab-content:errors', error => {
            if (error.id === this.section.id) {
                this.errors.push(error.error[0])
            }
        })
    }
}
</script>