import { mapGetters } from 'vuex'

export default {
    props: {
        isTabSectionPart: {
            type: Boolean,
            required: false,
            default: false
        },
        lang: {
            type: String,
            required: true,
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        }
    },

    data () {
        return {
            builderId: null
        }
    },

    computed: {
        ...mapGetters({
            contentIds: 'contentIds'
        })
    },

    methods: {
        async store (type) {
            let { data } = await axios.post(`${this.urlBase}/api/content-builder/${this.builderId}/${type}`, this.form)

            if (!this.isTabSectionPart) {
                window.events.$emit('part:created', {
                    data,
                    contentBuilderId: this.builderId
                })

                this.cancel()
            } else {
                window.events.$emit('tab-content:created', data)
            }
        },

        genericCancel () {
            if (this.isTabSectionPart) {
                window.events.$emit('tab-content:cancel-add')

                return
            }

            window.events.$emit('add-part:cancel', this.builderId)
        }
    },

    mounted () {
        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]
    }
}