<template>
    <div 
        class="content"
        v-html="answer.text.data"
    >
        
    </div>
</template>

<script>
import questionMarking from '../../../../../../mixins/questionMarking'

export default {
    mixins: [ questionMarking ]    
}
</script>
