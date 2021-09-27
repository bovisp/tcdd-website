import { omit } from 'lodash-es'

export const SET_QUESTIONS = (state, questions) => state.questions = questions

export const SET_AVAILABLE_EDITORS = (state, availableEditors) => state.availableEditors = availableEditors

export const SET_QUESTION = (state, question) => state.question = question

export const SET_TEMPORARY_ID = (state, questionId) => state.tempId = questionId

export const SET_QUESTION_TYPE_DATA = (state, data) => state.questionTypeData = data

export const SET_TEST_QUESTION_DATA = (state) => {
    state.testQuestionData = omit(state.question, [
        'author', 'editors', 'marking_guide_en', 'marking_guide_fr', 'name_en',
        'name_fr', 'tags', 'questionCategory', 'question_category_id', 'section_id',
        'sectionName', 'section', 'categoryName', 'contentBuilder'
    ])

    state.testQuestionData.type = state.question.type

    state.testQuestionData.questionTypeData = state.questionTypeData.data
}

export const SET_DUPLICATE_QUESTION_DATA = (state, data) => {
    state.question.id = data.questionId

    state.contentBuilder = data.contentBuilder
}

export const SET_DUPLICATE_QUESTION_TYPE_DATA = (state, data) => {
    state.questionTypeData.data = data
}