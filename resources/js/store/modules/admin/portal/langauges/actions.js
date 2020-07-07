export const fetch = async ({ commit }) => {
    let { data: languages } = await axios.get(`${urlBase}/api/admin/portal/languages`)

    commit('SET_LANGUAGES', languages.data)

    return
}

export const setEdit = async ({ commit }, language) => {
    await commit('SET_LANGUAGE', language)

    return
}