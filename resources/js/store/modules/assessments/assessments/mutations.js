import { orderBy, find } from 'lodash-es'

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

export const SET_LOCK_STATUS = (state, status) => state.lockStatus = status ? true : false