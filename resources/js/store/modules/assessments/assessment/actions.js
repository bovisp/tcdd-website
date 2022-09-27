export const start = async ({}, assessmentId) => {
    return axios.post(`${urlBase}/api/assessment/${assessmentId}/attempt`)
}

export const checkTimeRemaining = async ({ state, dispatch, commit }) => {
    let { data: timeRemaining } = await axios.get(`${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}/time`)

    if (timeRemaining <= 0) {
        await dispatch('submitAssessment')
    }

    if (timeRemaining <= 5) {
        window.events.$emit('assessment:five-min-warning')
    }

    await commit('SET_TIME_REMAINING', timeRemaining)
}

export const fetch = async ({ commit, dispatch, state }, payload) => {
    let { data: attempt } = await axios.get(`${urlBase}/api/assessment/${payload.assessment.id}/attempt/${payload.attempt.id}`)

    await commit('SET_ATTEMPT', attempt.data)

    await dispatch('checkTimeRemaining')

    await commit('SET_CURRENT_PAGE')

    if (!localStorage.getItem(`assessment_${state.attempt.id}`)) {
        localStorage.setItem(`assessment_${state.attempt.id}`, JSON.stringify({}))
    }

    let answersFromServer = JSON.parse(state.attempt.answers)

    if (!answersFromServer && localStorage.getItem(`assessment_${state.attempt.id}`)) {
        state.form = JSON.parse(localStorage.getItem(`assessment_${state.attempt.id}`))

        await dispatch('persistAnswersFromStorage')

        return
    }

    if (!answersFromServer) {
        return
    }

    await commit('SET_ATTEMPT_STORAGE')
}

export const deactivateParticipant = async ({ state }) => {
    await axios.patch(`${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}`)
}

export const changePage = async ({ commit }, page) => {
    await commit('SET_CURRENT_PAGE', page)

    window.events.$emit('assessment:page-changed')
}

export const getTotalScore = async ({ state, commit }) => {
    let totalScore = 0

    for await (let page of state.attempt.pages) {
        if (page.content.length) {
            for await (let item of page.content) {
                if (item.items[0].type === 'Question') {
                    totalScore += item.items[0].question_score
                }
            }
        }
    }

    await commit('SET_TOTAL_SCORE', totalScore)
}

export const getCurrentPageScore = async ({ state, commit }) => {
    let currentPageScore = 0

    for await (let item of state.currentPage.content) {
        if (item.items[0].type === 'Question') {
            currentPageScore += item.items[0].question_score
        }
    }

    await commit('SET_CURRENT_PAGE_SCORE', currentPageScore)
}

export const updateAttemptForm = async ({ commit, dispatch }, payload) => {
    await commit('UPDATE_ATTEMPT_FORM', payload)

    await dispatch('submitUpdatedForm', payload)
}

export const fetchAttemptForm = async ({ commit, state }) => {
    if (localStorage.getItem(`assessment_${state.attempt.id}`)) {
        await commit('SET_ATTEMPT_FORM', JSON.parse(localStorage.getItem(`assessment_${state.attempt.id}`)))
    }
}

export const submitUpdatedForm = async ({ state }, answer) => {
    await axios.patch(`${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}/answers`, {
        answer
    })
}

export const setReviewStatus = async ({ commit }, reviewStatus) => {
    await commit('SET_REVIEW_STATUS', reviewStatus)
}

export const fetchReviewData = async ({ commit }) => {
    await commit('SET_ATTEMPT_REVIEW')
}

export const goToQuestion = async ({ commit }, question) => {
    await commit('SET_REVIEW_STATUS', false)

    await commit('SET_CURRENT_PAGE', question.page) 
}

export const submitAssessment = async ({ state }) => {
    let answers = localStorage.getItem(`assessment_${state.attempt.id}`)

    localStorage.removeItem(`assessment_${state.attempt.id}`)

    let { data } = await axios.patch(
        `${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}/submit`, {
            answers
        }
    )

    window.location.href = `${urlBase}/users/${data}`
}

export const pushMultipleChoiceData = async ({ commit }, payload) => {
    await commit('PUSH_TO_MULTIPLE_CHOICE_ARR', payload)
}

export const setIncompleteQuestions = async ({ commit }, payload) => {
    await commit('SET_INCOMPLETE_QUESTIONS', payload)
}

export const persistAnswersFromStorage = async ({ state }) => {
    await axios.patch(
        `${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}/all-answers`, {
            answers: JSON.stringify(state.form)
        }
    )
}