<template>
    <section
        class="section"
    >
        <div class="columns is-centered">
            <div 
                class="column"
                v-if="!creating && !updating"
            >
                <nav class="level">
                    <div class="level-left"></div>
                    <div class="level-right">
                        <div class="level-item">
                            <b-button 
                                @click.prevent="creating = true"
                                type="is-text"
                            >{{ trans('js_pages_assessments_assessments_assessments.createassessment') }}</b-button>
                        </div>
                    </div>
                </nav>

                <assessments-index 
                    v-if="!creating && !updating"
                />
            </div>

            
            
            <div 
                class="column is-three-fourths"
                v-if="updating"
            >
                <assessments-edit />
            </div>

            <div 
                class="column is-half"
                v-if="creating"
            >
                <assessments-create />
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
        window.events.$on('assessments:edit', () => {
            this.updating = true
        })

        window.events.$on('assessments:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('assessments:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>