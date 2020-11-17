export const fetch = async ({ commit }) => {
    let { data: issues } = await axios.get(`${urlBase}/api/issues`)

    commit('SET_ISSUES', issues.data)

    return
}

export const setEdit = async ({ commit }, issue) => {
    await commit('SET_ISSUE', issue)

    return
}