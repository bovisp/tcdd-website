import { isEmpty, find, map, filter } from 'lodash-es'

export const getMissingProp = async ({ commit, state }, missingProp) => {
    let { data: prop } = await axios.get(`${urlBase}/api/assessments/${state.assessment.id}/missingprop/${missingProp}`)

    await commit('SET_MISSING_PROP', {
        updatedProp: prop.data,
        propName: missingProp
    })

    return prop
}

export const fetch = async ({ commit, state }) => {
    let { data: assessments } = await axios.get(`${urlBase}/api/assessments`)

    await commit('SET_ASSESSMENTS', assessments.data)

    if (!isEmpty(state.assessment)) {
        commit('SET_ASSESSMENT', find(assessments.data, p => p.id === state.assessment.id))
    }

    return
}

export const setEdit = async ({ commit }, assessment) => {
    await commit('SET_ASSESSMENT', assessment)

    await commit('SET_TOTAL_SCORE', assessment.total_score)

    return
}

export const fetchPage = async ({ state, commit }, page) => {
    await commit('SET_PAGE', {})

    let data = await axios.get(`${urlBase}/api/assessments/${state.assessment.id}/pages?page=${page ? page : ''}`)

    await commit('SET_PAGE', data.data)

    await commit('SET_CURRENT_PAGE_SCORE')

    await commit('SET_TOTAL_SCORE', data.data.total_score)
}

export const addPage = async ({ state, commit, dispatch }) => {
    let { data: page } = await axios.post(`${urlBase}/api/assessments/${state.assessment.id}/page`)

    await dispatch('fetchPage', page.number)

    await commit('UPDATE_PAGE_NUMBER', 1)

    await commit('SET_CURRENT_PAGE_SCORE')

    await commit('SET_TOTAL_SCORE', page.total_score)
}

export const destroyPage = async ({ dispatch, commit, state }) => {
    await axios.delete(`${urlBase}/api/assessments/${state.assessment.id}/page/${state.page.id}`)

    await commit('UPDATE_PAGE_NUMBER', -1)
}

export const updatePageNumber = async ({ state }, payload) => {
    await axios.patch(`${urlBase}/api/assessments/${state.assessment.id}/page`, payload)
}

export const fetchAvailableQuestions = async ({ commit, state }) => {
    let { data: questions } = await axios.get(`${urlBase}/api/assessments/${state.assessment.id}/questions`)

    await commit('SET_AVAILABLE_QUESTIONS', questions.data)
}

export const addQuestionToPage = async ({ state }, payload) => {
    await axios.post(`${urlBase}/api/assessments/${state.assessment.id}/page/${state.page.id}/add-question`, payload)
}

export const addContentToPage = async ({ state }) => {
    return axios.post(`${urlBase}/api/assessments/${state.assessment.id}/page/${state.page.id}/add-content`)
}

export const changeCurrentPageItemOrder = async ({ state, commit }, payload) => {
    await axios.patch(`${urlBase}/api/assessments/${state.assessment.id}/page/${state.page.id}/change-order`, payload)
}

export const deleteAssessmentPageItem = async ({ state }, itemId) => {
    await axios.delete(`${urlBase}/api/assessments/${state.assessment.id}/page/content/${itemId}`)
}

export const activateParticipant = async ({ dispatch, state, }, userId) => {
    await axios.patch(`${urlBase}/api/assessments/${state.assessment.id}/participants/activate/${userId}`)

    await dispatch('fetchAssessment', state.assessment.id)
}

export const setAssessmentLockStatus = async ({ commit, state }) => {
    let { data: status } = await axios.patch(`${urlBase}/api/assessments/${state.assessment.id}/lock`)

    await commit('SET_LOCK_STATUS', status)
}

export const duplicateAssessment = async ({ state, commit, dispatch }, form) => {
    let { data: assessment } = await axios.post(`${urlBase}/api/assessments/${state.assessment.id}/duplicate`)

    await commit('SET_ASSESSMENT', assessment.data)

    await commit('REMOVE_ATTEMPT_ANSWERS')

    await commit('REMOVE_ATTEMPTS')

    await commit('SET_DUPLICATION_STATUS', true)

    return
}

export const fetchAttempt = async ({ commit, state }, attemptId) => {
    let { data: attempt } = await axios.get(`${urlBase}/api/assessments/${state.assessment.id}/attempts/${attemptId}`)

    await commit('PUSH_ATTEMPT', attempt.data)
}

export const fetchAttempts = async ({ commit, state }) => {
    let { data: attempts } = await axios.get(`${urlBase}/api/assessments/${state.assessment.id}/attempts`)

    await commit('PUSH_ATTEMPTS', attempts.data)
}

export const fetchAssessment = async ({ commit }, assessmentId) => {
    let { data: assessment } = await axios.get(`${urlBase}/api/assessments/${assessmentId}`)

    await commit('SET_ASSESSMENT', assessment.data)
}

export const fetchParticipantAnswers = async ({ commit, state }) => {
    let { data: participantAnswers } = await axios.get(`${urlBase}/api/assessments/${state.assessment.id}/answers`)

    await commit('SET_ATTEMPT_ANSWERS', participantAnswers.data)
}

export const fetchParticipantAnswer = async ({ commit, state }, attemptId) => {
    let { data: participantAnswer } = await axios.get(`${urlBase}/api/assessments/${state.assessment.id}/attempt/${attemptId}/answer`)

    await commit('PUSH_ATTEMPT_ANSWER', participantAnswer.data)
}

export const setParticipantAnswer = async ({ commit }, participantAnswer) => {
    await commit('SET_ATTEMPT_ANSWER', participantAnswer)
}

export const updateMark = async ({ commit, state, dispatch }, payload) => {
    if (payload.id && payload.attemptId === null) {
        let { data } = await axios.patch(
            `${urlBase}/api/assessments/${state.assessment.id}/attempt/${state.participantAnswer.id}/mark/${payload.id}`,
            payload
        )

        await commit('UPDATE_MARK', data.data)

        window.events.$emit('assessment:mark-update', data.data)
    } else if (!payload.id && payload.attemptId === null) {
        let { data } = await axios.post(`${urlBase}/api/assessments/${state.assessment.id}/attempt/${state.participantAnswer.id}/mark`, payload)

        await commit('PUSH_NEW_MARK', data.data)

        window.events.$emit('assessment:mark-update', data.data)
    } else if (!payload.id && payload.attemptId !== null) {
        let { data } = await axios.post(`${urlBase}/api/assessments/${state.assessment.id}/attempt/${payload.attemptId}/mark`, payload)

        await commit('PUSH_NEW_MARK_ATTEMPT', {
            data: data.data,
            attempt: payload.attemptId
        })

        window.events.$emit('assessment:mark-update-attempt', {
            data: data.data,
            attemptId: payload.attemptId
        })
    } else if (payload.id && payload.attemptId !== null) {
        let { data } = await axios.patch(
            `${urlBase}/api/assessments/${state.assessment.id}/attempt/${payload.attemptId}/mark/${payload.id}`,
            payload
        )

        await commit('UPDATE_MARK_OF_ATTEMPT', {
            data: data.data,
            attempt: payload.attemptId
        })

        window.events.$emit('assessment:mark-update-attempt', {
            data: data.data,
            attemptId: payload.attemptId
        })
    }

    await dispatch('fetchParticipantAnswers')
}

export const updateAssessmentMarkingCompletion = async ({ commit }, payload) => {
    await commit('UPDATE_ASSESSMENT_MARKING_COMLETION', payload)
}

export const updateMarkScore = async ({ state, commit }, payload) => {
    let { data } = await axios.patch(
        `${urlBase}/api/assessments/${state.assessment.id}/attempt/${payload.attempt.id}/mark/${payload.mark.id}/update-score`,
        payload
    )

    await commit('UPDATE_MARK_OF_ATTEMPT', {
        data: data.data,
        attempt: payload.attempt.id
    })

    window.events.$emit('assessment:mark-update-attempt', {
        data: data.data,
        attemptId: payload.attempt.id
    })
}

export const removeMarkingCompleted = async ({ commit }) => {
    await commit('REMOVE_MARKING_COMPLETED')
}

export const setReviewStatusAll = async ({ commit, state }, status) => {
    let { data: participantAnswers } = await axios.patch(`${urlBase}/api/assessments/${state.assessment.id}/review/all`, { status })

    await commit('SET_ATTEMPT_ANSWERS', participantAnswers.data)
}

export const setReviewStatus = async ({ commit, state }, payload) => {
    let { data: participantAnswers } = await axios.patch(`${urlBase}/api/assessments/${state.assessment.id}/attempts/${payload.attemptId}/review`, { payload })

    await commit('SET_ATTEMPT_ANSWERS', participantAnswers.data)
}

export const removeInstructor = async ({ commit, state }, instructor) => {
    let { data } = await axios.delete(`${urlBase}/api/assessments/${state.assessment.id}/instructors`, {
        data: { instructor }
    })

    await commit('REMOVE_INSTRUCTOR', instructor)

    return data
}

export const removeParticipant = async ({ commit, state, dispatch }, participant) => {
    let { data } = await axios.delete(`${urlBase}/api/assessments/${state.assessment.id}/participants`, {
        data: { participant }
    })

    await commit('REMOVE_PARTICIPANT', participant)

    let markedAttempts = filter(state.attemptAnswers, attempt => attempt.marked)

    if (markedAttempts.length === state.assessment.participants.length) {
        console.log('true')
        await dispatch('updateMarkingCompletion', true)
    } else {
        await dispatch('updateMarkingCompletion', false)
    }

    return data
}

export const addInstructors = async({ commit, state }, instructors) => {
    let { data } = await axios.post(`${urlBase}/api/assessments/${state.assessment.id}/instructors`, {
        users: map(instructors, user => user.id)
    })

    await commit('ADD_INSTRUCTORS', data.data.instructors)

    return data
}

export const addParticipants = async({ commit, state, dispatch }, participants) => {
    let { data } = await axios.post(`${urlBase}/api/assessments/${state.assessment.id}/participants`, {
        users: map(participants, user => user.id)
    })

    await commit('ADD_PARTICIPANTS', data.data.participants)

    await dispatch('updateMarkingCompletion', false)

    return data
}

export const updateMarkingCompletion = async({ commit, state }, status) => {
    let { data } = await axios.patch(`${urlBase}/api/assessments/${state.assessment.id}/update-completion`, {
        status
    })

    await commit('UPDATE_ASSESSMENT_MARKING_COMLETION', data.data) 
}