import { isEmpty, find, get } from 'lodash-es'

export const SET_ATTEMPT = (state, attempt) => state.attempt = attempt

export const SET_TIME_REMAINING = (state, timeRemaining) => state.attempt.time_remaining = timeRemaining

export const SET_CURRENT_PAGE = async (state, page = 1) => {
    if (isEmpty(state.currentPage)) {
        state.currentPage = find(state.attempt.pages, ['number', 1])
    }

    state.currentPage = find(state.attempt.pages, ['number', page])
}

export const SET_TOTAL_SCORE = (state, totalScore) => state.totalScore = totalScore

export const SET_CURRENT_PAGE_SCORE = (state, currentPageScore) => state.currentPageScore = currentPageScore

export const UPDATE_ATTEMPT_FORM = (state, payload) => {
    if (!get(state.form, `question_${payload.id}`)) {
        state.form[`question_${payload.id}`] = {}
    }

    state.form[`question_${payload.id}`][payload.key] = payload.data

    localStorage.setItem(`assessment_${state.attempt.id}`, JSON.stringify(state.form))
}

export const SET_ATTEMPT_FORM = (state, payload) => {
    state.form = payload
}