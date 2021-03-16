<template>
    <div 
        v-if="user.assessments && user.assessments.length"
        class="w-full"
    >
        <h2 class="text-2xl">
            Active assessments
        </h2>

        <table>
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

                    <th class="p-2"></th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="assessment in user.assessments"
                    :key="assessment.id"
                    class="border-b"
                >
                    <td class="p-2">
                        {{ assessment.section.name }}
                    </td>

                    <td class="p-2">
                        {{ assessment.assessment_type.name }}
                    </td>

                    <td class="p-2">
                        {{ assessment.name }}
                    </td>

                    <td class="p-2 text-center">
                        <a 
                            :href="`${urlBase}/assessment/${assessment.id}`"
                        >
                            Enter exam
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    }    
}
</script>