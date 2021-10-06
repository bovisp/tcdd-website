<template>
    <div 
        v-if="!isEmpty(data)"
        class="w-full"
    >
        <template v-if="!data.editingPart">
            <component 
                :is="`Final${pascalCase(data.builderType.type) }`"
                :data="data"
                :id="id"
            ></component>
        </template>

        <div v-if="data.editingPart">
            <form>
                <edit-content 
                    :data="data"
                    :id="id"
                />

                <update-buttons 
                    @update="update({
                        type: 'content',
                        id: currentContentBuilder.id,
                        partDataId: data.data.id,
                        partId: data.id
                    })"
                    @cancel="cancel"
                />
            </form>
        </div>
    </div>
</template>

<script>
import updateContentBuilder from '../../../../mixins/updateContentBuilder'

export default {
    mixins: [
        updateContentBuilder
    ],

    methods: {
        cancel () {
            this.cancelEditingPart({
                id: this.id,
                partId: this.data.id
            })
        }
    }
}
</script>

<style>
.content p:last-child {
    margin-bottom: 0 !important;
}
</style>