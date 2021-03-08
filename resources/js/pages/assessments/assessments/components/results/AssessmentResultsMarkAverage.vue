<template>
    <div>
        {{ average }}
    </div>
</template>

<script>
import { reduce, find } from 'lodash-es'

export default {
    props: {
        attempts: {
            type: Array,
            required: true
        },
        question: {
            type: Object,
            required: true
        }
    },

    computed: {
        average () {
            return ((reduce(this.attempts, (result, value, key) => {
                let mark = find(value.marks, m => m.question_id === this.question.id)

                return result + parseFloat(mark.mark) 
            }, 0)) / this.attempts.length).toFixed(1)
        }
    }
}
</script>