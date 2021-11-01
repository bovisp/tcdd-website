<template>
    <div 
        v-if="!isEmpty(data)"
    >
        <template v-if="!data.editingPart && !isEmpty(data)">
            <component 
                :is="`Final${ pascalCase(data.builderType.type) }`"
                :data="data"
                :id="id"
            ></component>
        </template>

        <template v-else>
            <edit-tabs 
                :data="data"
                :id="id"
                @tabs:toggle-update-button="showUpdateButton = !showUpdateButton"
            />

            <update-buttons 
                :show-update-button="showUpdateButton"
                @update="update({
                    type: 'tab',
                    id: currentContentBuilder.id,
                    partDataId: data.data.id,
                    partId: data.id
                })"
                @cancel="cancel"
            />
        </template>
    </div>
</template>

<script>
import { filter, isEmpty, isNumber, forIn, remove, isInteger, has } from 'lodash-es'
import { pascalCase } from 'change-case'
import updateContentBuilder from '../../../../mixins/updateContentBuilder'

export default {
    mixins: [
        updateContentBuilder
    ],

    data () {
        return {
            showUpdateButton: false
        }
    },

    watch: {
        errors: {
            deep: true,

            handler () {
                forIn(this.errors, (value, key) => {
                    if (key.includes('tabs')) {
                        this.$buefy.dialog.alert({
                            title: 'Error',
                            message: value[0],
                            type: 'is-danger',
                            ariaRole: 'alertdialog',
                            ariaModal: true
                        })
                    }
                })
            }
        }
    },

    methods: {
        isEmpty,

        pascalCase,

        async cancel () {
            let destroyKeys = []

            for await (const [key, tab] of Object.entries(this.data.data.tabs)) {
                if (!isInteger(tab.id)) {
                    if (has(tab, 'content')) {
                        await axios.delete(`${this.urlBase}/api/parts/tabs/cancel`, {
                            data: { tab }
                        })
                    }

                    destroyKeys.push(parseInt(key))
                }
            }

            for (var i = destroyKeys.length -1; i >= 0; i--) {
                this.data.data.tabs.splice(destroyKeys[i],1);
            }

            await this.cancelEditingPart({
                id: this.id,
                partId: this.data.id
            })
        },

        async cancelUpdatingTab () {
            for await (const tab of this.part.data.tabSections) {
                if (!isEmpty(tab.content) && !isNumber(tab.id)) {
                    await axios.delete(`${this.urlBase}/api/parts/tabs/cancel`, {
                        data: { tab }
                    })
                }
            }

            this.part.data.tabSections = filter(this.part.data.tabSections, tab => isNumber(tab.id))

            this.editing = false

            window.events.$emit('part:edit-cancel')
        }
    },

    mounted () {
        window.events.$on('part:reset-update-button', () => this.showUpdateButton = false)
    }
}
</script>