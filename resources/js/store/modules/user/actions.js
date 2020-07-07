export const fetch = async ({ commit }, userId) => {
    let { data: user } = await axios.get(`${urlBase}/api/users/${userId}`)

    commit('SET_USER', user.data)

    return
}

export const me = async ({ commit }) => {
    let { data: user } = await axios.get(`${urlBase}/api/users/me`)

    commit('SET_ME', user.data)

    return
}