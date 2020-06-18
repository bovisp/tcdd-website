export const fetch = async ({ commit }) => {
    let { data: sections } = await axios.get(`${urlBase}/api/sections`)

    commit('SET_SECTIONS', sections.data)

    return
}

export const setEdit = async ({ commit }, section) => {
    await commit('SET_SECTION', section)

    return
}