import { find, forEach, merge, findIndex } from 'lodash-es'

export const setErrors = (state, payload) => state.errors = payload

export const clearErrors = state => state.errors = {}

export const SET_CONTENTBUILDER = (state, contentBuilder) => state.contentBuilder.push(contentBuilder)

export const UPDATE_EDIT_STATUS = (state, contentBuilderId) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === contentBuilderId)

    contentBuilder.editing = !contentBuilder.editing

    if (!contentBuilder.editing) {
        for (const part of contentBuilder.parts) {
            part.editingPart = false
        }
    }
}

export const ADD_NEW_FORM = (state, payload) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === payload.id)

    switch (payload.type) {
        case 'content':
            contentBuilder.new = {
                content: '',
                content_builder_type_id: 2,
                is_tab_section: payload.isTabSectionPart
            }

            break
        case 'tab':
            contentBuilder.new = {
                caption: '',
                title: '',
                content_builder_type_id: 5,
                tabs: []
            }

            break
    }
}

export const UPDATE_EDIT_FORM = (state, payload) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === payload.currentContentBuilder.id)

    if (!contentBuilder.edit) {
        contentBuilder.edit = []
    }

    let editObj = find(contentBuilder.edit, part => part.partDataId === payload.partDataId)

    if (!editObj) {
        contentBuilder.edit.push({
            partDataId: payload.partDataId,
            payload: payload.payload
        })

        return
    }

    if (!payload.partial) {
        editObj.payload = payload.payload

        return
    }


    for (const [key, value] of Object.entries(payload.payload)) {
        builder.new.payload[key] = value
    }   
}

export const UPDATE_NEW_FORM = (state, payload) => {
    forEach(state.contentBuilder, async builder => {
        if (builder.id === payload.currentContentBuilder.id) {
            for await (const [key, value] of Object.entries(payload.payload)) {
                builder.new[key] = value
            }
        }
    })
}

export const ADD_PART =  (state, payload) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === payload.id)

    if (!contentBuilder.new.isTabSectionPart) {
        contentBuilder.parts.push(merge(payload.data, {editingPart:false}))

        contentBuilder.new = null

        window.events.$emit('part:add-cancel')
    } else {
        // window.events.$emit('tab-content:created', data)
    }
}

export const EDIT_PART =  (state, payload) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === payload.id)

    let part = find(contentBuilder.parts, part => part.id === payload.partId)

    part.editingPart = !part.editingPart
}

export const UPDATE_PART = (state, {data, payload}) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === payload.id)

    let part = find(contentBuilder.parts, part => part.id === payload.partId)

    part = Object.assign(part, data)
}

export const CANCEL_EDITING_PART = (state, payload) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === payload.id)

    let part = find(contentBuilder.parts, part => part.id === payload.partId)

    part.editingPart = false
}

export const DESTROY_PART = (state, payload) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === payload.id)

    let index = findIndex(contentBuilder.parts, part => part.id === payload.partId)

    contentBuilder.parts.splice(index, 1)

    window.events.$emit('part:hide-delete-part-confirm')
}

export const CHANGE_PART_ORDER = (state, {parts, payload}) => {
    let contentBuilder = find(state.contentBuilder, builder => builder.id === payload.id)

    contentBuilder.parts = parts
}