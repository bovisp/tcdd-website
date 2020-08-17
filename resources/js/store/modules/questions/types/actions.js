export const fetch = async ({ commit }) => {
    let { data: questionTypes } = await axios.get(`${urlBase}/api/questions/types`)

    commit('SET_QUESTION_TYPES', questionTypes.data)

    return
}

export const setEdit = async ({ commit }, questionType) => {
    await commit('SET_QUESTION_TYPE', questionType)

    return
}