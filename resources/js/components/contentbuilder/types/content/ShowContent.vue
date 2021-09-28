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
                <edit-content 
                    :data="data"
                />

                <div class="flex my-2">
                    <button 
                        class="button is-small is-info"
                        @click.prevent="update('content')"
                    >
                        {{ trans('generic.update') }}
                    </button>

                    <button 
                        class="button is-small is-text has-text-dark ml-auto"
                        @click.prevent="cancel"
                    >
                        {{ trans('generic.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { pascalCase } from 'change-case'
import { isEmpty } from 'lodash-es'
import updateContentBuilder from '../../../../mixins/updateContentBuilder'

export default {
    mixins: [
        updateContentBuilder
    ],

    data () {
        return {
            form: {
                content: ''
            }
        }
    },

    methods: {
        pascalCase,

        isEmpty,

        cancel () {
            this.editing = false
            
            window.events.$emit('part:edit-cancel')
        },

        setContent () {
            this.form.content = this.part.data.content
        }
    },

    mounted () {
        window.events.$on('content:update-form', content => this.form.content = content)
    }
}
</script>

<style>
.content p:last-child {
    margin-bottom: 0 !important;
}
</style>