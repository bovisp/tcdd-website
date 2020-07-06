export const fetch = async ({ commit }) => {
    let { data: categories } = await axios.get(`${urlBase}/api/admin/portal/categories`)

    commit('SET_CATEGORIES', categories.data)

    return
}

export const setEdit = async ({ commit }, category) => {
    await commit('SET_CATEGORY', category)

    return
}