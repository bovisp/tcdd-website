import { forEach, isEmpty, find } from 'lodash-es'
import VueScrollTo from 'vue-scrollto'

export const setContentBuilder = async ({ commit }, contentBuilderId) => {
    let { data } = await axios.get(`${urlBase}/api/content-builder/${contentBuilderId}`)

    await commit('SET_CONTENTBUILDER', data.data, { root:true })
}

export const updateEditStatus = async ({ commit }, contentBuilderId) => {
    await commit('UPDATE_EDIT_STATUS', contentBuilderId, { root:true })
}

export const addNewPart = async ({ commit }, payload) => {
    await commit('ADD_NEW_FORM', payload, { root:true })
}

export const updateNewForm = async ({ commit }, payload) => {
    await commit('UPDATE_NEW_FORM', payload, { root:true })
}

export const updateEditForm = async ({ commit }, payload) => {
    await commit('UPDATE_EDIT_FORM', payload, { root:true })
}

export const createPart = async ({rootState, commit}, payload) => {
    forEach(rootState.contentBuilder, async builder => {
        if (builder.id === payload.id) {
            let form = {}

            if (payload.isTabSectionPart && builder.new) {
                form = builder.new.tabs[builder.new.activeTab].data
            } else if (payload.isTabSectionPart && !builder.new) {
                let editingPart = find(builder.edit, part => {
                    return part.partDataId === payload.tabPartDataId
                })

                form = editingPart.payload.tabs[editingPart.payload.activeTab].data
            } else {
                form = builder.new
            }

            let { data } = await axios.post(`${urlBase}/api/content-builder/${payload.id}/${payload.type}`, form)

            await commit('ADD_PART', {data, id: payload.id, isTabSectionPart: payload.isTabSectionPart}, { root: true })

            VueScrollTo.scrollTo(`#part-${data.id}`)
        }
    })
}

export const editPart = async ({commit}, payload) => {
    await commit('EDIT_PART', payload, { root: true })
}

export const updatePart = async ({commit, rootState}, payload) => {
    forEach(rootState.contentBuilder, async builder => {
        if (builder.id === payload.id) {
            forEach(builder.edit, async part => {
                if (part.partDataId === payload.partDataId) {
                    let { data } = await axios.patch(`${urlBase}/api/parts/${payload.partDataId}/${payload.type}`, part.payload)
                    
                    await commit('UPDATE_PART', {data, payload}, { root: true })

                    window.events.$emit('part:reset-update-button')
                }
            })
        }
    })
}

export const cancelEditingPart = async({commit}, payload) => {
    await commit('CANCEL_EDITING_PART', payload, { root: true })
}

export const destroyPart = async ({rootState, commit}, payload) => {
    
    await axios.delete(`${urlBase}/api/parts/${payload.partId}`, {
        data: {
            type: payload.type
        }
    })

    await commit('DESTROY_PART', payload, { root: true })
}

export const changePartOrder = async ({rootState, commit}, payload) => {
    forEach(rootState.contentBuilder, async builder => {
        if (builder.id === payload.id) {
            let { data } = await axios.patch(`${urlBase}/api/content-builder/${builder.id}/change-order`, {
                moved: payload.event.moved.element.id,
                newOrderNumber: payload.event.moved.newIndex + 1,
                oldOrderNumber: payload.event.moved.oldIndex + 1
            })

            await commit('CHANGE_PART_ORDER', {parts: data.data.parts, payload}, { root: true })
        }
    })
}

export const cancelAddingTab = async ({rootState}, payload) => {
    forEach(rootState.contentBuilder, async builder => {
        if (builder.id === payload.id) {
            for await (const tab of builder.new.tabs) {
                if (!isEmpty(tab.content)) {
                    await axios.delete(`${urlBase}/api/parts/tabs/cancel`, {
                        data: { tab }
                    })
                }
            }

            window.events.$emit('part:add-cancel')
        }
    })
}

export const removeFile = async ({}, files) => {
    await axios.delete(`${urlBase}/uploads`, {
        data: { files }
    })
}

export const updateActiveTab = async ({commit}, payload) => {
    await commit('UPDATE_ACTIVE_TAB', payload, { root: true })
}