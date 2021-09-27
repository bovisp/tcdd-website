export const fetch = async ({ commit }) => {
    let { data: articles } = await axios.get(`${urlBase}/api/articles`)

    await commit('SET_ARTICLES', articles.data)
}

export const createTempArticle = async ({ commit }) => {
    let { data } = await axios.post(`${urlBase}/api/articles/id`)

    await commit('SET_ARTICLE', data.data)

    await commit('SET_CONTENT_ID', data.data.contentBuilder, { root:true })
}

export const destroy = async ({ commit, state }) => {
    await axios.delete(`${urlBase}/api/articles/${state.article.id}`)

    await commit('SET_ARTICLE', null) 

    await commit('SET_CONTENT_ID', null, { root:true })
}