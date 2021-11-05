<template>
    <div class="assessment-attempt-item">
        <template v-if="content.items[0].type === 'ContentBuilder'">
            <div class="mb-6">
                <component 
                    v-for="part in orderBy(parts, ['sort_order'], ['asc'])"
                    :key="part.id"
                    :is="`Final${ ucfirst(part.builderType.type) }`"
                    :id="contentBuilderForLang.model_id"
                    :data="part"
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
            parts: [],
            contentBuilderForLang: null
        }
    },

    methods: {
        orderBy,

        ucfirst
    },

    async mounted () {
        if (this.content.items[0].type === 'ContentBuilder') {
            this.contentBuilderForLang = find(this.content.items, ['lang', this.currentLang])

            let { data } = await axios.get(`${this.urlBase}/api/content-builder/${this.contentBuilderForLang.model_id}`)
            
            this.parts = data.data.parts
        }
    }
}
</script>