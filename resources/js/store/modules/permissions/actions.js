export const fetch = async ({ commit }) => {
    let { data: permissions } = await axios.get(`${urlBase}/api/permissions`)

    commit('SET_PERMISSIONS', permissions.data)

    return
}

export const setEdit = async ({ commit }, permission) => {
    await commit('SET_PERMISSION', permission)

    return
}