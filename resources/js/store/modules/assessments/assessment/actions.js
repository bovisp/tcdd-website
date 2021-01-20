import Axios from "axios"

export const start = async ({}, assessmentId) => {
    return axios.post(`${urlBase}/api/assessment/${assessmentId}/attempt`)
}