import { orderBy, find, findIndex, isEmpty } from 'lodash-es'

export const SET_ASSESSMENTS = (state, assessments) => state.assessments = assessments

export const SET_ASSESSMENT = (state, assessment) => state.assessment = assessment

export const SET_PAGES = (state, pages) => state.pages = orderBy(pages, ['number'], ['asc'])

export const SET_CURRENT_PAGE = async (state, page = null) => {
    if (!state.currentPage && state.pages.length === 0) {
        state.currentPage = null
    }

    if (!state.currentPage && state.pages.length !== 0) {
        state.currentPage = state.pages[0]
    }

    if (state.currentPage && state.pages.length === 0) {
        state.currentPage = null
    }

    if (state.currentPage && !page) {
        let pageFound = false

        for await (let p of state.pages) {
            if (p.id === state.currentPage.id) {
                pageFound = true
            }
        }

        if (pageFound === false) {
            state.currentPage = state.pages[0]
        }
    }

    if (page) {
        state.currentPage = find(state.pages, ['number', page])
    }
}

export const SET_CURRENT_PAGE_SCORE = async (state) => {
    if (!state.currentPage || !state.currentPage.data.length) {
        state.currentPageScore = null
        return
    }

    let currentPageScore = 0

    for await (let pageItem of state.currentPage.data) {
        if (pageItem.type === 'Question') {
            currentPageScore += pageItem.model.assessment_page_content_items[0].question_score
        }
    }

    state.currentPageScore = currentPageScore
}

export const ADD_PAGE = (state, page) => state.pages.push(page)

export const SET_TOTAL_SCORE = (state, totalScore) => state.totalScore = totalScore

export const SET_AVAILABLE_QUESTIONS = (state, questions) => state.availableQuestions = questions

export const SET_LOCK_STATUS = (state, status) => state.assessment.locked = status

export const SET_DUPLICATE_STATUS = (state, status) => state.isDuplicate = status

export const PUSH_ATTEMPT = async (state, attempt) => {
    state.attempts.push(attempt)
}

export const PUSH_ATTEMPTS = (state, attempts) => state.attempts = attempts

export const SET_ATTEMPT_ANSWERS = (state, attemptAnswers) => state.attemptAnswers = attemptAnswers

export const PUSH_ATTEMPT_ANSWER = (state, attemptAnswer) => state.attemptAnswers.push(attemptAnswer)

export const SET_ATTEMPT_ANSWER = (state, participantAnswer) => state.participantAnswer = participantAnswer

export const CLEAR_PARTICIPANT_ANSWER = (state) => state.participantAnswer = null

export const PUSH_NEW_MARK = (state, mark) => state.participantAnswer.marks.push(mark)

export const UPDATE_MARK = (state, mrk) => {
    let index = findIndex(state.participantAnswer.marks, m => m.id === mrk.id)

    state.participantAnswer.marks[index] = mrk
}

export const PUSH_NEW_MARK_ATTEMPT = (state, payload) => {
    let index = findIndex(state.attemptAnswers, answer => answer.id === payload.attempt)

    state.attemptAnswers[index].marks.push(payload.data)
}

export const UPDATE_MARK_OF_ATTEMPT = async (state, payload) => {
    let index = await findIndex(state.attemptAnswers, answer => answer.id === payload.attempt)

    let markIndex = await findIndex(state.attemptAnswers[index].marks, mark => mark.id === payload.data.id)

    state.attemptAnswers[index].marks[markIndex] = payload.data
}

export const UPDATE_ASSESSMENT_MARKING_COMLETION = (state, payload) => {
    if (!isEmpty(state.assessment)) {
        state.assessment.marking_completed = true

        state.assessment.marking_completed_on = payload.markingCompletedOn
    }
}