<template>
    <div>
        <b-field>
            <b-input 
                placeholder="Add an optional tab title..."
                size="is-medium"
                class="borderless-input borderless-input-md"
                v-model="form.title"
            ></b-input>
        </b-field>

        <tab-list 
            :tab-list="form.tabs"
            :lang="lang"
            @tabs:update-tab-count="updateTabs"
            @set-tabs="setTabs"
        />

        <b-field>
            <b-input 
                placeholder="Add an optional tab caption..."
                class="borderless-input"
                v-model="form.caption"
            ></b-input>
        </b-field>
    </div>
</template>

<script>
import { isEmpty } from 'lodash-es'

export default {
    props: {
        lang: {
            type: String,
            required: true,
        },
        contentBuilderId: {
            type: Number,
            required: false,
            default: null
        },
        data: {
            type: Object,
            required: false
        }
    },

    data () {
        return {
            form: {
                content_builder_type_id: 5,
                title: '',
                caption: '',
                tabs: []
            }
        }
    },

    // watch: {
    //     form: {
    //         deep: true,

    //         handler () {
    //             console.log(this.form)
    //             window.events.$emit('tabs:update-form', this.form)
    //         }
    //     }
    // },

    methods: {
        setTabs (tabs) {
            this.form.tabs = tabs
        },

        updateTabs (tabs) {
            this.form.tabs = tabs
        }
    },

    mounted () {
        if (!isEmpty(this.data)) {
            this.form.title = this.data.title

            this.form.caption = this.data.caption

            this.form.tabs = this.data.tabSections
        }

        // window.events.$on('tabs:update-tab-list', tabs => this.form.tabs = tabs)
    }
}
</script>