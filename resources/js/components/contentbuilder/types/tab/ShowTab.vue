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
import { filter, isEmpty, isNumber } from 'lodash-es'
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

    methods: {
        isEmpty,

        pascalCase,

        cancel () {
            this.cancelEditingPart({
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
    }
}
</script>