import { findIndex, isEmpty, filter } from 'lodash-es'

export const SET_ASSESSMENTS = (state, assessments) => state.assessments = assessments

export const SET_ASSESSMENT = (state, assessment) => state.assessment = assessment

export const SET_PAGE = (state, page) => state.page = page

export const SET_CURRENT_PAGE_SCORE = async (state) => {
    if (!state.page || !state.page.data.length) {
        state.currentPageScore = null
        return
    }

    let currentPageScore = 0

    for await (let pageItem of state.page.data) {
        if (pageItem.type === 'Question') {
            currentPageScore += pageItem.model.assessment_page_content_items[0].question_score
        }
    }

    state.currentPageScore = currentPageScore
}

export const SET_TOTAL_SCORE = (state, totalScore) => state.totalScore = totalScore

export const SET_AVAILABLE_QUESTIONS = (state, questions) => state.availableQuestions = questions

export const SET_LOCK_STATUS = (state, status) => state.assessment.locked = status ? true : false

export const SET_DUPLICATE_STATUS = (state, status) => state.isDuplicate = status

export const PUSH_ATTEMPT = async (state, attempt) => {
    state.attempts.push(attempt)
}

export const PUSH_ATTEMPTS = (state, attempts) => state.attempts = attempts

export const SET_ATTEMPT_ANSWERS = (state, attemptAnswers) => state.attemptAnswers = attemptAnswers

export const PUSH_ATTEMPT_ANSWER = (state, attemptAnswer) => state.attemptAnswers.push(attemptAnswer)

export const SET_ATTEMPT_ANSWER = (state, participantAnswer) => state.participantAnswer = participantAnswer

export const CLEAR_PARTICIPANT_ANSWER = (state) => state.participantAnswer = null

export const PUSH_NEW_MARK = (state, mark) => state.participantAnswer.marks.push(mark)

export const UPDATE_MARK = (state, mrk) => {
    let index = findIndex(state.participantAnswer.marks, m => m.id === mrk.id)

    state.participantAnswer.marks[index] = mrk
}

export const PUSH_NEW_MARK_ATTEMPT = (state, payload) => {
    let index = findIndex(state.attemptAnswers, answer => answer.id === payload.attempt)

    state.attemptAnswers[index].marks.push(payload.data)
}

export const UPDATE_MARK_OF_ATTEMPT = async (state, payload) => {
    let index = await findIndex(state.attemptAnswers, answer => answer.id === payload.attempt)

    let markIndex = await findIndex(state.attemptAnswers[index].marks, mark => mark.id === payload.data.id)

    state.attemptAnswers[index].marks[markIndex] = payload.data
}

export const UPDATE_ASSESSMENT_MARKING_COMLETION = (state, payload) => {
    if (!isEmpty(state.assessment)) {
        state.assessment.marking_completed = payload.marking_completed

        state.assessment.marking_completed_on = payload.marking_completed_on
    }
}

export const REMOVE_MARKING_COMPLETED = (state) => {
    state.assessment.marking_completed = false

    state.assessment.marking_completed_on = null
}

export const REMOVE_INSTRUCTOR = (state, instructor) => {
    state.assessment.editors = filter(state.assessment.editors, editor => editor.pivot.editor_id !== instructor.editor_id)
}

export const REMOVE_PARTICIPANT = (state, participant) => {
    state.assessment.participants = filter(state.assessment.participants, u => {
        return u.pivot.participant_id !== participant.participant_id
    })
}

export const ADD_INSTRUCTORS = (state, instructors) => {
    state.assessment.editors = instructors
}

export const ADD_PARTICIPANTS = (state, participants) => {
    state.assessment.participants = participants
}

export const SET_DUPLICATION_STATUS = (state, status) => {
    state.isDuplicating = status
}

export const UPDATE_PAGE_NUMBER = (state, increment) => state.assessment.pages += increment

export const REMOVE_ATTEMPT_ANSWERS = (state) => {
    state.attemptAnswers = []
}

export const REMOVE_ATTEMPTS = (state) => {
    state.attempts = []
}