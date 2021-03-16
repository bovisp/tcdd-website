<template>
    <div 
        v-if="user.markedAssessments && user.markedAssessments.length"
        class="w-full"
    >
        <h2 class="text-2xl">
            Completed assessments
        </h2>

        <table class="w-full">
            <thead>
                <tr class="border-b-2">
                    <th class="p-2 text-left">
                        Section
                    </th>

                    <th class="p-2 text-left">
                        Type
                    </th>

                    <th class="p-2 text-left">
                        Title
                    </th>

                    <th class="p-2 text-left">
                        Marked
                    </th>

                    <th class="p-2 text-left">
                        Score
                    </th>

                    <th class="p-2">
                        Review
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