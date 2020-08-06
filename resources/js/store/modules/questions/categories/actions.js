export const fetch = async ({ commit }) => {
    let { data: questionCategories } = await axios.get(`${urlBase}/api/questions/categories`)

    commit('SET_QUESTION_CATEGORIES', questionCategories.data)

    return
}

export const setEdit = async ({ commit }, questionCategory) => {
    await commit('SET_QUESTION_CATEGORY', questionCategory)

    return
}