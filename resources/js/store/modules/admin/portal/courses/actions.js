export const fetch = async ({ commit }) => {
    let { data: courses } = await axios.get(`${urlBase}/api/admin/portal/courses`)

    commit('SET_COURSES', courses.data)

    return
}

export const setEdit = async ({ commit }, course) => {
    await commit('SET_COURSE', course)

    return
}