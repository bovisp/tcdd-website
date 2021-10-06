import contentBuilderData from './contentBuilder'
import { mapActions } from 'vuex'

export default {
    mixins: [
        contentBuilderData
    ],
    
    props: {
        isTabSectionPart: {
            type: Boolean,
            required: false,
            default: false
        },
        type: {
            type: String,
            required: true
        }
    },

    methods: {
        ...mapActions({
            addNewPart: 'contentbuilder/addNewPart',
            createPart : 'contentbuilder/createPart'
        }),

        genericCancel () {
            if (this.isTabSectionPart) {
                window.events.$emit('tab-content:cancel-add')

                return
            }

            window.events.$emit('part:add-cancel')
        }
    },

    mounted () {
        this.addNewPart({
            type: this.type,
            id: this.id,
            isTabSectionPart: this.isTabSectionPart
        })
    }
}