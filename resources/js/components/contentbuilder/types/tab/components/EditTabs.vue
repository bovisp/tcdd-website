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
            :tabs="form.tabs"
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
export default {
    props: {
        lang: {
            type: String,
            required: true,
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

    watch: {
        form: {
            deep: true,

            handler () {
                window.events.$emit('tabs:update-form', this.form)
            }
        }
    },

    methods: {
        setTabs (tabs) {
            this.form.tabs = tabs
        },

        updateTabs (tabs) {
            this.form.tabs = tabs
        }
    },

    mounted () {
        window.events.$on('tabs:update-tab-list', tabs => this.form.tabs = tabs)
    }
}
</script>