import { isEmpty, find, get } from 'lodash-es'

export const SET_ATTEMPT = (state, attempt) => state.attempt = attempt

export const SET_TIME_REMAINING = (state, timeRemaining) => state.attempt.time_remaining = timeRemaining

export const SET_CURRENT_PAGE = async (state, page = 1) => {
    if (isEmpty(state.currentPage)) {
        state.currentPage = find(state.attempt.pages, ['number', 1])
    }

    state.currentPage = find(state.attempt.pages, ['number', page])
}

export const SET_TOTAL_SCORE = (state, totalScore) => state.totalScore = totalScore

export const SET_CURRENT_PAGE_SCORE = (state, currentPageScore) => state.currentPageScore = currentPageScore

export const UPDATE_ATTEMPT_FORM = (state, payload) => {
    if (!get(state.form, `question_${payload.id}`)) {
        state.form[`question_${payload.id}`] = {}
        state.form[`question_${payload.id}`][payload.key] = {}
    }

    state.form[`question_${payload.id}`][payload.key]['data'] = payload.data
    state.form[`question_${payload.id}`][payload.key]['timestamp'] = payload.timestamp

    localStorage.setItem(`assessment_${state.attempt.id}`, JSON.stringify(state.form))
}

export const SET_REVIEW_STATUS = (state, reviewStatus) => state.reviewStatus = reviewStatus

export const SET_ATTEMPT_REVIEW = async (state) => {
    let attemptReviewQuestionsArr = []

    let attemptObj = JSON.parse(localStorage.getItem(`assessment_${state.attempt.id}`))
    
    for await (let page of state.attempt.pages) {
        if (page.content.length) {
            for await (let item of page.content) {
                if (item.items[0].type === 'Question') {
                    let { data: question } = await axios.get(`
                        ${urlBase}/api/assessment/${state.attempt.assessment.id}/attempt/${state.attempt.id}/question/${item.items[0].model_id}
                    `)

                    let requiredQuestionKeys = []

                    switch (question.data.type) {
                        case 'essay':
                            requiredQuestionKeys.push('text')
                            break
                        case 'multiple_choice':
                            requiredQuestionKeys.push('answers')
                            break
                        case 'drawing':
                            if (question.data.data.text_answer) {
                                requiredQuestionKeys.push('drawing', 'text')
                                break
                            } else {
                                requiredQuestionKeys.push('drawing')
                                break
                            }
                    }

                    let existingQuestionKeys = []

                    if (attemptObj[`question_${item.items[0].model_id}`]) {
                        existingQuestionKeys.push(...Object.keys(attemptObj[`question_${item.items[0].model_id}`]))
                    }

                    attemptReviewQuestionsArr.push({
                        question_number: item.items[0].question_number,
                        question_score: item.items[0].question_score,
                        question_id: item.items[0].model_id,
                        page: page.number,
                        required_answer_keys: requiredQuestionKeys,
                        existing_question_keys: existingQuestionKeys
                    })
                }
            }
        }
    }

    state.attemptReview.questions = attemptReviewQuestionsArr
}

export const SET_ATTEMPT_STORAGE = async (state) => {
    if (!localStorage.getItem(`assessment_${state.attempt.id}`)) {
        localStorage.setItem(`assessment_${state.attempt.id}`, JSON.stringify({}))
    }

    let answersFromServer = JSON.parse(state.attempt.answers)

    if (!answersFromServer) {
        return
    }

    let answersFromStorage = JSON.parse(localStorage.getItem(`assessment_${state.attempt.id}`))

    for await (let answer of Object.keys(answersFromServer)) {
        if (!answersFromStorage[answer]) {
            answersFromStorage[answer] = {}
        }

        for await (let key of Object.keys(answersFromServer[answer])) {
            if (answersFromStorage[answer][key] && (answersFromServer[answer][key]['timestamp'] > answersFromStorage[answer][key]['timestamp'])) {
                answersFromStorage[answer][key]['data'] = answersFromServer[answer][key]['data']
            } else if (!answersFromStorage[answer][key]) {
                answersFromStorage[answer][key] = {}

                answersFromStorage[answer][key]['data'] = answersFromServer[answer][key]['data']
            }
        }
    }

    localStorage.setItem(`assessment_${state.attempt.id}`, JSON.stringify(answersFromStorage))

    state.form = answersFromStorage
}