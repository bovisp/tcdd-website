import Axios from "axios"

export const start = async ({}, assessmentId) => {
    return axios.post(`${urlBase}/api/assessment/${assessmentId}/attempt`)
}

export const checkTimeRemaining = async ({ state, dispatch }) => {
    let { data: timeRemaining } = await axios.get(`${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}/time`)

    if (timeRemaining <= 0) {
        await dispatch('deactivateParticipant')

        window.location.href = `${urlBase}/users/${state.attempt.participant.id}`
    }
}

export const fetch = async ({ commit, dispatch }, payload) => {
    let { data: attempt } = await axios.get(`${urlBase}/api/assessment/${payload.assessment.id}/attempt/${payload.attempt.id}`)

    await commit('SET_ATTEMPT', attempt.data)
}

export const deactivateParticipant = async ({ state }) => {
    await axios.patch(`${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}`)
}