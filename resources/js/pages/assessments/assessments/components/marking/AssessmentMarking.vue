<template>
    <div>
        <assessment-marking-question
            v-if="markingQuestion"
            :question-marking-obj="questionMarkingObj"
            @assessments:mark-return-to-table="markingQuestion = false"
        />

        <assessment-marking-question-menu
            v-if="!marking && !markingQuestion"
        />

        <assessment-marking-index 
            v-if="!marking && !markingQuestion"
        />

        <assessment-marking-participant 
            v-if="marking"
            @assessments:mark-return-to-table="marking = false"
        />
    </div>
</template>

<script>
export default {
    data () {
        return {
            marking: false,
            markingQuestion: false,
            questionMarkingObj: {}
        }
    },

    mounted () {
        window.events.$on('assessment:marking', () => {
            this.marking = true
        })

        window.events.$on('assessment:mark-question', payload => {
            this.questionMarkingObj = payload
            
            this.markingQuestion = true
        })
    }
}
</script>