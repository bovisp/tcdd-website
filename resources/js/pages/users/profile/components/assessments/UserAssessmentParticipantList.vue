<template>
    <div 
        v-if="typeof user.assessmentParticipant !== 'undefined' && user.assessmentParticipant.length"
        class="w-full"
    >
        <h2 class="text-2xl">
            Active assessments
        </h2>

        <ul class="ml-2">
            <li
                v-for="assessment in user.assessmentParticipant"
                :key="assessment.id"
                class="my-1"
            >
                <a 
                    :href="`${urlBase}/assessments/${assessment.id}`"
                >
                    {{ assessment.name }}
                </a>
            </li>
        </ul>
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