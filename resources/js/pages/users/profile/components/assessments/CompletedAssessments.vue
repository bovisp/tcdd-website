<template>
    <div 
        v-if="user.markedAssessments && user.markedAssessments.length"
        class="w-full"
    >
        <h2 class="text-2xl">
            {{ trans('js_pages_users_profile_components_assessments_completedassessments.completedassessments') }}
        </h2>

        <table class="w-full">
            <thead>
                <tr class="border-b-2">
                    <th class="p-2 text-left">
                        {{ trans('js_pages_users_profile_components_assessments_completedassessments.section') }}
                    </th>

                    <th class="p-2 text-left">
                        {{ trans('js_pages_users_profile_components_assessments_completedassessments.type') }}
                    </th>

                    <th class="p-2 text-left">
                        {{ trans('js_pages_users_profile_components_assessments_completedassessments.title') }}
                    </th>

                    <th class="p-2 text-left">
                        {{ trans('js_pages_users_profile_components_assessments_completedassessments.marked') }}
                    </th>

                    <th class="p-2 text-left">
                        {{ trans('js_pages_users_profile_components_assessments_completedassessments.score') }}
                    </th>

                    <th class="p-2">
                        {{ trans('js_pages_users_profile_components_assessments_completedassessments.review') }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="attempt in user.markedAssessments"
                    :key="attempt.id"
                    class="border-b"
                >
                    <td class="p-2">
                        {{ attempt.section }}
                    </td>

                    <td class="p-2">
                        {{ attempt.type }}
                    </td>

                    <td class="p-2">
                        {{ attempt.name }}
                    </td>

                    <td class="p-2">
                        {{ dayjs(attempt.marked).format('YYYY-MM-DD') }}
                    </td>

                    <td class="p-2">
                        {{ attempt.participant_score }}/{{ attempt.total_score }} ({{ attempt.percentage }}%)
                    </td>

                    <td class="p-2 text-center">
                        <button 
                            class="btn btn-sm btn-text text-sm text-blue-500"
                            @click.prevent="($emit('assessment:review', attempt.id))"
                            v-if="attempt.show"
                        >
                            {{ trans('js_pages_users_profile_components_assessments_completedassessments.review') }}
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import dayjs from 'dayjs'

export default {
    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },
    
    methods: {
        dayjs
    }
}
</script>