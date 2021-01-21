import Axios from "axios"

export const start = async ({}, assessmentId) => {
    return axios.post(`${urlBase}/api/assessment/${assessmentId}/attempt`)
}

export const checkTimeRemaining = async ({ state, dispatch, commit }) => {
    let { data: timeRemaining } = await axios.get(`${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}/time`)

    if (timeRemaining <= 0) {
        await dispatch('deactivateParticipant')

        window.location.href = `${urlBase}/users/${state.attempt.participant.id}`
    }

    await commit('SET_TIME_REMAINING', timeRemaining)
}

export const fetch = async ({ commit, dispatch }, payload) => {
    let { data: attempt } = await axios.get(`${urlBase}/api/assessment/${payload.assessment.id}/attempt/${payload.attempt.id}`)

    await commit('SET_ATTEMPT', attempt.data)

    await dispatch('checkTimeRemaining')

    await commit('SET_CURRENT_PAGE')
}

export const deactivateParticipant = async ({ state }) => {
    await axios.patch(`${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}`)
}

export const changePage = async ({ commit }, page) => {
    await commit('SET_CURRENT_PAGE', page)
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