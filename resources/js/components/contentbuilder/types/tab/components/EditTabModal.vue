<template>
    <b-modal
        v-if="!isEmpty(tabToEdit)"
        v-model="modalActive"
        has-modal-card
        trap-focus
        aria-role="dialog"
        aria-label="Edit tab"
        aria-modal
    >
        <form action="">
            <div class="modal-card" style="width: auto">
                <header class="modal-card-head">
                    <p class="modal-card-title">Edit tab</p>
                    <button
                        type="button"
                        class="delete"
                        @click="closeTabEditModal"
                    />
                </header>

                <section class="modal-card-body">
                    <b-field label="Tab title">
                        <b-input
                            type="text"
                            v-model="form.label"
                            required
                        ></b-input>
                    </b-field>

                    <b-field label="Tab order">
                        <b-select 
                            v-model="form.order"
                            expanded
                            :disabled="numTabs === 1"
                        >
                            <option
                                v-for="i in numTabs"
                                :value="i"
                                :key="i"
                            >
                                {{ i }}
                            </option>
                        </b-select>
                    </b-field>
                </section>

                <footer class="modal-card-foot">
                    <b-button
                        label="Cancel"
                        @click="closeTabEditModal" 
                    />
                    <b-button
                        label="Save"
                        type="is-primary" 
                        @click.prevent="updateTab"
                    />
                </footer>
            </div>
        </form>
    </b-modal>
</template>

<script>
import { map, sortedUniq, isEmpty } from 'lodash-es'

export default {
    props: {
        numTabs: {
            type: Number,
            required: true
        }
    },

    data () {
        return {
            modalActive: false,
            tabToEdit: null,
            originalOrder: null,
            form: {
                label: '',
                order: null
            }
        }
    },

    methods: {
        sortedUniq,

        map,

        isEmpty,

        closeTabEditModal () {
            this.modalActive = false
        },

        updateTab () {
            window.events.$emit('tabs:update-edited-tab', {
                originalOrder: this.originalOrder,
                tabToEdit: {
                    label: this.form.label,
                    order: this.form.order,
                    id: this.tabToEdit.id
                }
            })

            this.closeTabEditModal()
        }
    },

    mounted () {
        window.events.$on('tabs:edit-tab', tab => {
            this.tabToEdit = tab

            this.originalOrder = tab.order

            this.form.label = this.tabToEdit.label

            this.form.order = this.tabToEdit.order

            this.modalActive = true
        })
    }
}
</script>