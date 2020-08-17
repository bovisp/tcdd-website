export const fetch = async ({ commit }) => {
    let { data: tags } = await axios.get(`${urlBase}/api/admin/tags`)

    commit('SET_TAGS', tags.data)

    return
}

export const setEdit = async ({ commit }, tag) => {
    await commit('SET_TAG', tag)

    return
}