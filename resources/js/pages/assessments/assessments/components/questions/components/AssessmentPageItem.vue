<template>
    <div class="flex w-full">
        <!-- <template v-if="data.type === 'ContentBuilder'">
            <div class="mb-6">
                <assessment-page-content-builder 
                    v-for="item in orderBy(data.items, ['id'], ['asc'])"
                    :key="item.id"
                    :content-builder-id="item.id"
                    :lang="item.lang"
                />
            </div>
        </template> -->

        <template v-if="data.type === 'Question'">
            <div class="mb-6">
                <assessment-page-question 
                    :question="data"
                />
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    }
}
</script>