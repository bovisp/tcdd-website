import { isEmpty, find } from 'lodash-es'

export const fetch = async ({ commit, state }) => {
    let { data: permissions } = await axios.get(`${urlBase}/api/permissions`)

    commit('SET_PERMISSIONS', permissions.data)

    if (!isEmpty(state.permission)) {
        commit('SET_PERMISSION', find(permissions.data, p => p.id === state.permission.id))
    }

    return
}

export const setEdit = async ({ commit }, permission) => {
    await commit('SET_PERMISSION', permission)

    return
}