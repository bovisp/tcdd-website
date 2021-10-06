import { pascalCase } from 'change-case'
import { isEmpty } from 'lodash-es'
import contentBuilderData from './contentBuilder'
import { mapActions } from 'vuex'

export default {
    mixins: [
        contentBuilderData
    ],
    
    props: {
        data: {
            type: Object,
            required: true
        },
        isTabSectionPart: {
            type: Boolean,
            required: false,
            default: false
        },
    },

    methods: {
        ...mapActions({
            update: 'contentbuilder/updatePart',
            cancelEditingPart: 'contentbuilder/cancelEditingPart'
        }),

        pascalCase,

        isEmpty
    }
}