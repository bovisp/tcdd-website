import { find, isEmpty, orderBy } from 'lodash-es'
import { mapGetters } from 'vuex'

export default {
    props: {
        id: {
            type: Number,
            required: true
        }
    },

    watch: {
        async id () {
            await this.setContentBuilder(this.id)
        }
    },

    computed: {
        ...mapGetters({
            contentBuilder: 'contentBuilder'
        }),

        currentContentBuilder () {
            return find(this.contentBuilder, builder => builder.id === this.id)
        },

        orderedParts () {
            if (!isEmpty(this.currentContentBuilder)) {
                return orderBy(this.currentContentBuilder.parts, ['order', 'asc'])
            }
        },

        editing () {
            if (!isEmpty(this.currentContentBuilder)) {
                return this.currentContentBuilder.editing
            }
        }
    }
}