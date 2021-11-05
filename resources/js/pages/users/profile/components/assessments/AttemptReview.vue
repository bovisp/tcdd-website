<template>
    <div class="w-full" v-if="attempt">
        <div class="flex items-center w-full">
            <h2 class="text-3xl font-medium mb-2">
                {{ trans('generic.review') }}: {{ attempt.title }}
            </h2>

            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                {{ trans('generic.returntoprofile') }}
            </button>
        </div>

        <div class="mt-4">
            <strong>{{ trans('generic.section') }}: </strong> {{ attempt.section }}
        </div>

        <div class="mt-2">
            <strong>{{ trans('generic.type') }}: </strong> {{ attempt.type }}
        </div>

        <div class="mt-2">
            <strong>{{ trans('generic.marked') }}: </strong> {{ dayjs(attempt.marked_on).format('YYYY-MM-DD') }}
        </div>

        <div class="mt-2">
            <strong>{{ trans('generic.score') }}: </strong> {{ attempt.participant_score }}/{{ attempt.total_score }} ({{ attempt.percentage }}%)
        </div>

        <hr class="my-6">

        <h3 class="text-2xl font-light mb-4">
            {{ trans('js_pages_users_profile_components_assessments_attemtpreview.results') }}
        </h3>

        <ul>
            <li
                v-for="question in orderBy(attempt.questions, ['number'], ['asc'])"
                :key="question.number"
                class="flex mb-6"
            >
                <p class="text-right mr-3 w-6 pt-4">
                    {{ question.number }}.
                </p>

                <div class="flex-1 mb-6">
                    <component 
                        v-for="part in orderBy(question.text, ['sort_order'], ['asc'])"
                        :key="part.id"
                        :is="`Final${ pascalCase(part.builderType.type) }`"
                        :data="part"
                        :id="question.content_builder_id"
                    ></component>

                    <h4 class="mb-4 font-medium text-lg">
                        {{ trans('generic.answer') }}:
                    </h4>

                    <component 
                        :is="`${pascalCase(question.type)}QuestionMarking`"
                        :answer="question.answer"
                        :question="question"
                    ></component>

                    <template v-if="question.type !== 'multiple_choice'">
                        <h4 class="my-4 font-medium text-lg">
                            {{ trans('generic.markercomments') }}:
                        </h4>

                        <div
                            v-html="question.mark.comment"
                            v-if="question.mark.comment"
                        ></div>

                        <div 
                            class="alert alert-blue"
                            v-else
                        >{{ trans('generic.nocomments') }}</div>
                    </template>

                    <div class="mt-4">
                        <strong>{{ trans('generic.score') }}: </strong> 

                        {{ question.mark.score }}/{{ question.score }} {{ trans('generic.points') }}
                    </div>

                    <p 
                        v-if="question.mark.marker"
                        class="text-sm"
                    >
                        <strong>{{ trans('generic.markedby') }}: </strong>{{ question.mark.marker.fullname }}
                    </p>
                </div>
            </li>
        </ul>

        <div class="flex items-center w-full">
            <button 
                class="btn btn-text ml-auto"
                @click.prevent="cancel"
            >
                <i class="fas fa-chevron-left mr-1"></i>
                {{ trans('generic.returntoprofile') }}
            </button>
        </div>
    </div>
</template>

<script>
import dayjs from 'dayjs'
import { orderBy } from 'lodash-es'
import { pascalCase } from 'change-case'

export default {
    props: {
        attemptId: {
            type: Number,
            required: true
        }
    },
    
    data () {
        return {
            attempt: null
        }
    },

    methods: {
        dayjs,

        orderBy,

        pascalCase,

        cancel () {
            this.$emit('assessment:review-cancel')
        }
    },

    async mounted () {
        let { data } = await axios.get(`${this.urlBase}/api/users/${this.authUser.id}/attempt/${this.attemptId}/review`)

        this.attempt = data.data
    }
}
</script>