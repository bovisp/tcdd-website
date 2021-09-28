export default {
    props: {
        data: {
            type: Object,
            required: true
        },
        editStatus: {
            type: Boolean,
            required: false,
            default: false
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        },
        isTabSectionPart: {
            type: Boolean,
            required: false,
            default: false
        },
    },

    data () {
        return {
            part: {},
            editing: false
        }
    },

    methods: {
        async update (type) {
            let { data } = await axios.patch(`${this.urlBase}/api/parts/${this.part.data.id}/${type}`, this.form)

            this.part.data = data

            this.cancel()
        }
    },

    mounted () {
        this.part = this.data

        window.events.$on('turn-editing-off', () => {
            this.cancel()
        })

        window.events.$on('part:edit', partId => {
            if (this.part.id === partId) {
                this.editing = true

                this.setContent()
            }
        })

        if (this.editStatus) {
            this.editing = true

            this.setContent()
        }
    }
}