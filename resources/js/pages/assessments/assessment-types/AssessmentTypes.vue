<template>
    <section
        class="section"
    >
        <div class="columns is-centered">
            <div class="column is-half">
                <nav
                    class="level"
                    v-if="!creating && !updating"
                >
                    <div class="level-left"></div>
                    <div class="level-right">
                        <div class="level-item">
                            <b-button 
                                @click.prevent="creating = true"
                                type="is-text"
                            >{{ trans('js_pages_assessments_assessment-types_assessmenttypes.addassessmenttype') }}</b-button>
                        </div>
                    </div>
                </nav>

                <assessment-types-edit 
                    v-if="updating"
                />

                <assessment-types-create 
                    v-if="creating"
                />

                <assessment-types-index 
                    v-if="!creating && !updating"
                />
            </div>
        </div>
    </section>
</template>

<script>
export default {
    data () {
        return {
            updating: false,
            creating: false
        }
    },

    mounted () {
        window.events.$on('assessment-types:edit', () => {
            this.updating = true
        })

        window.events.$on('assessment-types:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('assessment-types:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>