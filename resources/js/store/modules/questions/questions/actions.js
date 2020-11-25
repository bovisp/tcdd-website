export const fetch = async ({ commit }) => {
    let { data: questions } = await axios.get(`${urlBase}/api/questions`)

    commit('SET_QUESTIONS', questions.data)

    return
}

export const fetchAvailableEditors = async ({commit}, questionId) => {
    let { data: availableEditors } = await axios.get(`${urlBase}/api/questions/${questionId}/editors`)

    commit('SET_AVAILABLE_EDITORS', availableEditors.data)

    return
}

export const setEdit = async ({ commit }, question) => {
    await commit('SET_QUESTION', question)

    return
}

export const createId = async ({ commit }) => {
    let { data } = await axios.post(`${urlBase}/api/questions/id`)

    await commit('SET_TEMPORARY_ID', data.questionId)

    await commit('SET_CONTENT_ID', data.contentBuilderId)
}

export const removeTempIds = async ({ commit }, questionId) => {
    await commit('SET_TEMPORARY_ID', null)

    await commit('SET_CONTENT_ID', null)
}

export const setContentBuilderIds = async ({commit}, contentBuilderIds) => {
    await commit('SET_CONTENT_ID', contentBuilderIds)
}

export const fetchQuestionTypeData = async ({commit}, questionId) => {
    let { data } = await axios.get(`${urlBase}/api/questions/${questionId}/data`)

    await commit('SET_QUESTION_TYPE_DATA', data)
}

export const fetchTestQuestionData = async ({commit}) => {
    await commit('SET_TEST_QUESTION_DATA')
}

export const duplicateQuestion = async ({commit, state}) => {
    let { data } = await axios.post(`${urlBase}/api/questions/${state.question.id}/duplicate`)

    await commit('SET_DUPLICATE_QUESTION_DATA', data)
    await commit('SET_TEMPORARY_ID', data.questionId)
    await commit('SET_CONTENT_ID', data.contentBuilder)
    await commit('SET_DUPLICATE_QUESTION_TYPE_DATA', data.questionData)
}