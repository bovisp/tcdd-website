<template>
    <div 
        v-if="!isEmpty(part)"
    >
        <template v-if="!editing && !isEmpty(part)">
            <component 
                :is="`Final${ pascalCase(part.builderType.type) }`"
                :part="part"
            ></component>
        </template>

        <template v-else>
            <edit-tabs 
                :lang="lang"
                :data="part.data"
            />

            <div class="level mt-2">
                <div class="level-left">
                    <div class="level-item">
                        <b-button
                            type="is-info"
                            size="is-small"
                            icon-left="pencil"
                            @click.prevent="update"
                        >Update tab</b-button>
                    </div>
                </div>

                <div class="level-right">
                    <div class="level-item">
                        <b-button
                            type="is-text"
                            size="is-small"
                            @click.prevent="cancelUpdatingTab"
                        >Cancel updating tab</b-button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import { filter, isEmpty, isNumber } from 'lodash-es'
import { pascalCase } from 'change-case'

export default {
    props: {
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        },
        lang: {
            type: String,
            required: false,
            default: ''
        },
        data: {
            type: Object,
            required: true
        },
    },

    data () {
        return {
            form: null,
            builderId: null,
            part: null,
            editing: false
        }
    },

    methods: {
        isEmpty,

        pascalCase,

        async update () {
            let { data } = await axios.patch(`${this.urlBase}/api/parts/${this.part.id}/tab`, this.form)

            this.part = data

            this.editing = false

            window.events.$emit('part:edit-cancel')
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
        this.part = this.data

        this.form = this.data.data

        this.builderId = this.contentBuilderId ? this.contentBuilderId : this.contentIds[this.lang]

        window.events.$on('tabs:update-tab-list', tabs => this.form.tabs = tabs)

        window.events.$on('tabs:update-form', form => this.form = form)

        window.events.$on('part:edit', partId => {
            if (this.part.id === partId) {
                this.editing = true
            }
        })

        window.events.$on('turn-editing-off', async () => {
            await this.cancelUpdatingTab()
        })
    }
}
</script>