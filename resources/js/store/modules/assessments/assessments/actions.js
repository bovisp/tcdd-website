import { isEmpty, find } from 'lodash-es'

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