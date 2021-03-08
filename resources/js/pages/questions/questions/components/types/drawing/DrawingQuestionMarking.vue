<template>
    <div>
        <img 
            :src="`${urlBase}${answer.drawing.data.replace('public', 'storage')}`" 
            alt="Drawing image for answer"
            class="block mx-auto max-w-full"
        >

        <div
            class="content mt-4"
            v-html="answer.text.data"
            v-if="answer.text"
        ></div>
    </div>
</template>

<script>
import questionMarking from '../../../../../../mixins/questionMarking'

export default {
    mixins: [ questionMarking ]    
}
</script>