<template>
    <div>
        <div
            class="mb-4"
        >
            <label 
                class="block text-gray-700 font-bold mb-2"
                :class="{ 'text-red-500': errors.title }"
                for="title"
            >
                Tab title
            </label>

            <input 
                type="text" 
                v-model="form.title"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                id="title"
                :class="{ 'border-red-500': errors.title }"
            >

            <p
                v-if="errors.title"
                v-text="errors.title[0]"
                class="text-red-500 text-xs"
            ></p>
        </div>

        <div class="mb-4" v-if="!type">
            <label 
                class="block text-gray-700 font-bold mb-2"
                for="content"
            >Content type</label>

            <select 
                class="form-select block w-full" 
                id="content"
                v-model="type"
                @input="creating = true"
            >
                <option value=""></option>

                <option
                    v-for="t in types"
                    :key="t.id"
                    :value="t.type"
                    v-text="ucfirst(t.type)"
                ></option>
            </select>
        </div>

        <div class="mb-4 flex justify-end" v-if="!type">
            <button 
                class="btn btn-text btn-sm text-sm ml-auto"
                @click.prevent="$emit('canceladd', data.id)"
            >
                Cancel adding tab
            </button>
        </div>

        <div
            v-if="creating"
        >
            <component 
                :is="`Add${ucfirst(type)}`"
                :edit-status="true"
                create-button-text="Save"
                create-button-classes="btn-sm text-sm btn-outline"
                :is-tab-section-part="true"
                :part-id="partId"
                :section-title="title"
                :lang="lang"
                @tab-part-section-content:created="pushData"
                @tab-content:cancel-add="$emit('canceladd', data.id)"
            ></component>
        </div>

        <div
            v-if="!creating && type"
            class="px-3"
        >
            <component 
                :is="`Show${ucfirst(type)}`"
                :content-builder-id="contentIds[lang]"
                :edit-status="false"
                :data="section"
            ></component>
        </div>

        <hr class="my-5">
    </div>
</template>

<script>
import ucfirst from '../../../../../helpers/ucfirst'
import { filter } from 'lodash-es'
import { mapGetters } from 'vuex'

export default {
    props: {
        data: {
            type: Object,
            required: true
        },
        partId: {
            type: Number,
            required: false,
            default: null
        },
        lang: {
            type: String,
            required: true,
        }
    },

    data () {
        return {
            form: {
                title: ''
            },
            section: {},
            types: [],
            type: null,
            creating: false,
            title: ''
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'questions/contentIds'
        }),
    },

    watch: {
        'form.title' () {
            window.events.$emit('tab-content:section', {
                id: this.section.id,
                title: this.form.title
            })
        },

        type () {
            this.title = this.form.title
        }
    },

    methods: {
        ucfirst,

        pushData (data) {
            if (this.partId) {
                this.section = data
                
                window.events.$emit('tab-content:section-data', data)
            } else {
                window.events.$emit('tab-content:section-data', {
                    id: this.section.id,
                    type: this.type,
                    data
                })
            }

            window.events.$emit('tab-content:cancel-adding-tab-section')

            this.creating = false
        },

        cancelTab () {
            this.form.title = '',
            this.type = ''
        }
    },

    async mounted () {
        this.section = this.data

        if (this.partId) {
            this.form.partId = this.partId
        }

        let { data: types } = await axios.get(`${this.urlBase}/api/parts/types`)

        this.types = filter(types.data, type => type.type !== 'tab')
    }
}
</script>