import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import state from './state'
import * as mutations from './mutations'
import * as actions from './actions'
import * as getters from './getters'

import user from './modules/user'
import sections from './modules/admin/sections'
import permissions from './modules/permissions'
import portalLanguages from './modules/admin/portal/langauges'
import portalCategories from './modules/admin/portal/categories'
import portalCourses from './modules/admin/portal/courses'
import assessmentTypes from './modules/assessments/assessment-types'
import assessments from './modules/assessments/assessments'
import questionCategories from './modules/questions/categories'

export default new Vuex.Store({
	state, 
	mutations, 
	actions,
	getters,
	modules: {
		user,
		sections,
		permissions,
		portalLanguages,
		portalCategories,
		portalCourses,
		assessmentTypes,
		assessments,
		questionCategories
	} 
})