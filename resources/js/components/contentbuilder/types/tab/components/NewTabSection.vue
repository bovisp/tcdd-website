<template>
    <div>
        <div
            class="form-group"
        >
            <label 
                :class="{ 'text-danger': errors.title }"
                for="title"
            >
                Tab title
            </label>

            <input 
                type="text" 
                v-model="form.title"
                class="form-control"
                id="title"
                :class="{ 'is-invalid': errors.title }"
            >

            <p
                v-if="errors.title"
                v-text="errors.title[0]"
                class="invalid-feedback"
            ></p>
        </div>

        <div class="form-group" v-if="!type">
            <label for="content">Content type</label>

            <select 
                class="form-control" 
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

        <div
            v-if="creating"
        >
            <component 
                :is="`Add${ucfirst(type)}`"
                :series-id="1"
                :edit-status="true"
                create-button-text="Save"
                create-button-classes="btn-sm btn-outline-secondary"
                :is-tab-section-part="true"
                :part-id="partId"
                :section-title="title"
                @tab-part-section-content:created="pushData"
            ></component>
        </div>

        <div
            v-if="!creating && type"
            class="px-3"
        >
            <component 
                :is="`Show${ucfirst(type)}`"
                :series-id="1"
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
        }
    },

    data () {
        return {
            form: {
                title: ''
            },
            section: {},
            errors: [],
            types: [],
            type: null,
            creating: false,
            title: ''
        }
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
        }
    },

    async mounted () {
        this.section = this.data

        if (this.partId) {
            this.form.partId = this.partId
        }

        let { data: types } = await axios.get('/api/parts/types')

        this.types = filter(types.data, type => type.type !== 'tab')
    }
}
</script>