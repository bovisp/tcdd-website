export const fetch = async ({ commit }, userId) => {
    let { data: user } = await axios.get(`${urlBase}/api/users/${userId}`)

    commit('SET_USER', user.data)

    return
}