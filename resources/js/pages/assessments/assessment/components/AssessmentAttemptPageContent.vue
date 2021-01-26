<template>
    <div class="assessment-attempt-item">
        <template v-if="content.items[0].type === 'ContentBuilder'">
            <div class="mb-6">
                <component 
                    v-for="part in orderBy(parts, ['sort_order'], ['asc'])"
                    :key="part.id"
                    :is="`Final${ ucfirst(part.builderType.type) }`"
                    :part="part"
                ></component>
            </div>
        </template>

        <template v-if="content.items[0].type === 'Question'">
            <div class="mb-6">
                <assessment-attempt-question 
                    :data="content"
                />
            </div>
        </template>
    </div>
</template>

<script>
import { orderBy, find } from 'lodash-es'
import ucfirst from '../../../../helpers/ucfirst'

export default {
    props: {
        content: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            parts: []
        }
    },

    methods: {
        orderBy,

        ucfirst
    },

    async mounted () {
        if (this.content.items[0].type === 'ContentBuilder') {
            let contentBuilderForLang = find(this.content.items, ['lang', this.currentLang])

            let { data } = await axios.get(`${this.urlBase}/api/content-builder/${contentBuilderForLang.model_id}`)
            
            this.parts = data.data.parts
        }
    }
}
</script>