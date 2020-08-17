export const fetch = async ({ commit }) => {
    let { data: questions } = await axios.get(`${urlBase}/api/questions`)

    commit('SET_QUESTIONS', questions.data)

    return
}

export const setEdit = async ({ commit }, question) => {
    await commit('SET_QUESTION', question)

    return
}