export const fetch = async ({ commit }) => {
    let { data: assessmentTypes } = await axios.get(`${urlBase}/api/assessments/assessment-types`)

    commit('SET_ASSESSMENT_TYPES', assessmentTypes.data)

    return
}

export const setEdit = async ({ commit }, assessmentType) => {
    await commit('SET_ASSESSMENT_TYPE', assessmentType)

    return
}