<template>
    <div>
        <div
            class="form-group"
        >
            <label 
                :class="{ 'text-danger': errors.length > 0 }"
                :for="`title-${section.id}`"
            >
                Tab title
            </label>

            <input 
                type="text" 
                v-model="form.title"
                class="form-control"
                :id="`title-${section.id}`"
                :class="{ 'is-invalid': errors.length > 0 }"
            >

            <p
                v-if="errors.length > 0"
                v-text="errors[0]"
                class="invalid-feedback"
            ></p>
        </div>

        <div
            class="form-group"
        >
            <h5 class="mt-4 mb-n3">
                Tab Content ({{ section.type }})
            </h5>

            <component 
                :is="`Show${ucfirst(section.type)}`"
                :series-id="1"
                :edit-status="editStatus"
                :data="section.content"
                :id="section.content_id"
                @edited="addToForm"
            ></component>
        </div>

        <div
            class="form-group mt-n4"
        >
            <button 
                class="btn btn-sm btn-outline-danger"
                @click="deleteTab"
            >
                Delete tab
            </button>
        </div>

        <hr class="my-4">
    </div>
</template>

<script>
import ucfirst from '../../../../../helpers/ucfirst'
import { merge } from 'lodash-es'

export default {
    props: {
        section: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            errors: [],
            editStatus: true,
            form: {
                title: '',
                data: {}
            }
        }
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