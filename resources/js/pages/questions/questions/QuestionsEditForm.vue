<template>
    <div>
        <p 
            class="mb-4 text-gray-700"
            v-if="question.author"
        >
            <strong>{{ trans('generic.owner') }}:</strong> {{ question.author.fullname }}
        </p>

        <b-field 
            :label="trans('generic.editors')"
            :type="errors.editors ? 'is-danger' : ''"
            :message="errors.editors ? errors.editors[0] : ''"
            class="w-full lg:w-1/3"
        >
            <multiselect 
                v-model="form.editors" 
                :options="availableEditors" 
                :multiple="true" 
                :placeholder="trans('js_pages_questions_questions_questionsedit.searcheditors')"
                label="fullname" 
                track-by="id" 
                :taggable="true"
            />
        </b-field>

        <b-field
            :label="trans('generic.section')"
            :type="errors.section_id ? 'is-danger' : ''"
            :message="errors.section_id ? errors.section_id[0] : ''"
            class="w-full lg:w-1/3"
        >
            <b-select 
                expanded
                v-model="form.section_id"
            >
                <option
                    :value="section.id"
                    v-for="section in sections"
                    :key="section.id"
                    v-text="section.name"
                ></option>
            </b-select>
        </b-field>

        <b-field
            :label="trans('generic.questioncategory')"
            :type="errors.question_category_id ? 'is-danger' : ''"
            :message="errors.question_category_id ? errors.question_category_id[0] : ''"
            class="w-full lg:w-1/3"
        >
            <b-select 
                expanded
                v-model="form.question_category_id"
            >
                <option
                    :value="questionCategory.id"
                    v-for="questionCategory in questionCategories"
                    :key="questionCategory.id"
                    v-text="questionCategory.name"
                ></option>
            </b-select>
        </b-field>

        <b-field 
            :label="trans('generic.nameenglish')"
            :type="errors.name_en ? 'is-danger' : ''"
            :message="errors.name_en ? errors.name_en[0] : ''"
            class="w-full lg:w-1/3"
        >
            <b-input v-model="form.name_en"></b-input>
        </b-field>

        <b-field 
            :label="trans('generic.namefrench')"
            :type="errors.name_fr ? 'is-danger' : ''"
            :message="errors.name_fr ? errors.name_fr[0] : ''"
            class="w-full lg:w-1/3"
        >
            <b-input v-model="form.name_fr"></b-input>
        </b-field>

        <b-field 
            :label="trans('generic.questiontextenglish')"
            :type="errors.description_en ? 'is-danger' : ''"
            :message="errors.description_en ? errors.description_en[0] : ''"
        >
            <content-builder 
                v-if="!isEmpty(question)"
                :id="question.contentBuilder.en"
            />
        </b-field>

        <b-field 
            :label="trans('generic.questiontextfrench')"
            :type="errors.description_fr ? 'is-danger' : ''"
            :message="errors.description_fr ? errors.description_fr[0] : ''"
        >
            <content-builder 
                v-if="!isEmpty(question)"
                :id="question.contentBuilder.fr"
            />
        </b-field>

        <b-field 
            :label="trans('generic.markingguideenglish')"
            :type="errors.marking_guide_en ? 'is-danger' : ''"
            :message="errors.marking_guide_en ? errors.marking_guide_en[0] : ''"
        >
            <vue-editor 
                v-model="form.marking_guide_en"
            ></vue-editor>
        </b-field>

        <b-field 
            :label="trans('generic.markingguidefrench')"
            :type="errors.marking_guide_fr ? 'is-danger' : ''"
            :message="errors.marking_guide_fr ? errors.marking_guide_fr[0] : ''"
        >
            <vue-editor 
                v-model="form.marking_guide_fr"
            ></vue-editor>
        </b-field>

        <b-field 
            :label="trans('generic.tags')"
            :type="errors.tags ? 'is-danger' : ''"
            :message="errors.tags ? errors.tags[0] : ''"
            class="w-full md:w-2/3 lg:w-1/2"
        >
            <multiselect 
                v-model="form.tags" 
                :options="avalaibleTags" 
                :multiple="true" 
                :placeholder="trans('generic.searchaddtag')" 
                :tag-placeholder="trans('generic.addasnewtag')"
                label="name" 
                track-by="id" 
                :taggable="true"
                @tag="tagModal"
            />
        </b-field>

        <hr class="my-6">

        <template v-if="questionTypeData.name">
            <h3 class="subtitle is-4">
                {{ capitalCase(questionTypeData.name) }} {{ trans('generic.questionsettings') }}
            </h3>
        </template>

        <template v-if="question.type">
            <component 
                :is="`${pascalCase(question.type)}QuestionEdit`"
                @question-type:update-data="updateQuestionTypeData"
            ></component>
        </template>

        <b-modal
            v-model="modalAddTag"
            has-modal-card
            trap-focus
            aria-role="dialog"
            :aria-label="`${trans('generic.add')} ${noCase(trans('generic.tag'))}`"
            aria-modal
        >
            <form action="">
                <div class="modal-card" style="width: auto">
                    <header class="modal-card-head">
                        <p class="modal-card-title">
                            {{ trans('generic.add') }} {{ currentLang === 'en' ? trans('generic.french') : trans('generic.english') }} {{ trans('generic.translationtag') }}: {{ tag }}
                        </p>

                        <button
                            type="button"
                            class="delete"
                            @click="close"
                        />
                    </header>

                    <section class="modal-card-body">
                        <p>{{ trans('generic.newtagmessage1') }} {{ currentLang === 'en' ? trans('generic.french') : trans('generic.english') }} 
                        {{ trans('generic.newtagmessage2') }} '{{ tag }}'. {{ trans('generic.newtagmessage3') }}</p>

                        <b-field 
                            :label="trans('generic.nameenglish')"
                            :type="errors.name_en || errors.name_fr ? 'is-danger' : ''"
                        >
                            <b-input v-model="tagTranslation"></b-input>

                            <p
                                v-if="errors.name_en || errors.name_fr"
                                v-text="currentLang === 'en' ? errors.name_en[0] : errors.name_fr[0]"
                                class="text-red-500 text-sm"
                            ></p>
                        </b-field>
                    </section>

                    <footer class="modal-card-foot">
                        <b-button
                            label="Cancel"
                            @click="close"
                        />

                        <b-button
                            :label="`${trans('generic.add')} ${noCase(trans('generic.tag'))}`"
                            type="is-info"
                            @click.prevent="storeTag"
                        />
                    </footer>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import { mapGetters } from 'vuex'
import setObject from '../../../helpers/setObject'
import { VueEditor, Quill } from 'vue2-editor'
import { noCase, capitalCase, pascalCase } from 'change-case'
import { isEmpty } from 'lodash-es'

export default {
    components: {
        Multiselect,
        VueEditor
    },

    props: {
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            form: {},
            tag: '',
            tagTranslation: '',
            modalAddTag: false
        }
    },

    computed: {
        ...mapGetters({
            availableEditors: 'questions/availableEditors',
            sections: 'sections/sections',
            questionCategories: 'questionCategories/questionCategories',
            avalaibleTags: 'tags/tags',
            questionTypeData: 'questions/questionTypeData'
        })
    },

    watch: {
        form: {
            deep: true,

            handler () {
                this.$emit('question:form-updated', this.form)
            }
        },

        question: {
            deep: true,

            handler () {
                this.form = setObject(this.question)
            }
        }
    },

    methods: {
        noCase,

        capitalCase, 
        
        pascalCase,

        isEmpty,

        tagModal (tag) {
            this.tag = tag

            this.modalAddTag = true
        },

        close () {
            this.tag = ''
            this.tagTranslation = ''

            this.$store.dispatch('clearErrors')

            this.modalAddTag = false
        },

        async storeTag () {
            let { data } = await axios.post(`${this.urlBase}/api/admin/tags`, {
                name_en: currentLang === 'en' ? this.tag : this.tagTranslation,
                name_fr: currentLang === 'en' ? this.tagTranslation : this.tag 
            })

            await this.fetchTags()

            await this.form.tags.push(data.data.tag)

            this.close()

            this.$buefy.toast.open({
                message: data.data.message,
                type: 'is-success'
            })
        },

        updateQuestionTypeData (data) {
            this.form.question_type_data = data
        }
    }
}
</script>