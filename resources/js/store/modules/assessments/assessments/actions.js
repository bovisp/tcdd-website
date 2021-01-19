import { isEmpty, find, orderBy } from 'lodash-es'

export const fetch = async ({ commit, state }) => {
    let { data: assessments } = await axios.get(`${urlBase}/api/assessments`)

    commit('SET_ASSESSMENTS', assessments.data)

    if (!isEmpty(state.assessment)) {
        commit('SET_ASSESSMENT', find(assessments.data, p => p.id === state.assessment.id))
    }

    return
}

export const setEdit = async ({ commit }, assessment) => {
    await commit('SET_ASSESSMENT', assessment)

    return
}

export const fetchPages = async ({ commit, dispatch }, assessmentId) => {
    let { data: pages } = await axios.get(`${urlBase}/api/assessments/${assessmentId}/page`)

    await commit('SET_PAGES', pages.data)

    await commit('SET_CURRENT_PAGE')

    await commit('SET_CURRENT_PAGE_SCORE')

    await dispatch('getTotalScore')
}

export const getTotalScore = async ({ state, commit }) => {
    let totalScore = 0

    for await (let page of state.pages) {
        let pageItems = page.data

        for await (let pageItem of pageItems) {
            if (pageItem.type === 'Question') {
                totalScore += pageItem.model.assessment_page_content_items[0].question_score
            }
        }
    }

    await commit('SET_TOTAL_SCORE', totalScore)
}

export const addPage = async ({ state, commit }) => {
    let { data: page } = await axios.post(`${urlBase}/api/assessments/${state.assessment.id}/page`)

    await commit('ADD_PAGE', page)

    await commit('SET_CURRENT_PAGE', page.number)

    await commit('SET_CURRENT_PAGE_SCORE')
}

export const setCurrentPage = async ({ commit }, page) => {
    await commit('SET_CURRENT_PAGE', page)

    await commit('SET_CURRENT_PAGE_SCORE')
}

export const destroyPage = async ({ dispatch, commit, state }, pageId) => {
    await axios.delete(`${urlBase}/api/assessments/page/${pageId}`)

    await dispatch('fetchPages', state.assessment.id)
}

export const updatePageNumber = async ({ dispatch, commit, state }, payload) => {
    console.log(payload)
    await axios.patch(`${urlBase}/api/assessments/${state.assessment.id}/page`, {
        newPageNumber: payload.newPageNumber,
        oldPageNumber: payload.oldPageNumber
    })

    await dispatch('fetchPages', state.assessment.id)

    await commit('SET_CURRENT_PAGE', payload.newPageNumber)
}

export const fetchAvailableQuestions = async ({ commit }, assessmentId) => {
    let { data: questions } = await axios.get(`${urlBase}/api/assessments/${assessmentId}/questions`)

    await commit('SET_AVAILABLE_QUESTIONS', questions.data)
}

export const addQuestionToPage = async ({ commit, state, dispatch }, payload) => {
    await axios.post(`${urlBase}/api/assessments/page/${state.currentPage.id}/add-question`, { payload })

    await dispatch('fetchPages', state.assessment.id)

    await commit('SET_CURRENT_PAGE', state.currentPage.number)

    await commit('SET_CURRENT_PAGE_SCORE')
}

export const addContentToPage = async ({ state }) => {
    return axios.post(`${urlBase}/api/assessments/page/${state.currentPage.id}/add-content`)
}

export const changeCurrentPageItemOrder = async ({ state, dispatch, commit }, payload) => {
    await axios.patch(`${urlBase}/api/assessment/page/${state.currentPage.id}/change-order`, payload)

    await dispatch('fetchPages', state.assessment.id)

    await commit('SET_CURRENT_PAGE', state.currentPage.number)
}

export const deleteAssessmentPageItem = async ({ state, dispatch, commit }, itemId) => {
    await axios.delete(`${urlBase}/api/assessments/page/content/${itemId}`)

    await dispatch('fetchPages', state.assessment.id)

    await commit('SET_CURRENT_PAGE', state.currentPage.number)
}

export const activateParticipant = async ({ dispatch, state }, payload) => {
    await axios.patch(`${urlBase}/api/assessments/participants/activate?id=${payload.participantId}&activated=${payload.isActivated}`)

    await dispatch('fetch')
}