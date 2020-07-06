import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import state from './state'
import * as mutations from './mutations'
import * as actions from './actions'
import * as getters from './getters'

import user from './modules/user'
import sections from './modules/admin/sections'
import portalLanguages from './modules/admin/portal/langauges'
import portalCourses from './modules/admin/portal/courses'

export default new Vuex.Store({
	state, 
	mutations, 
	actions,
	getters,
	modules: {
		user,
		sections,
		portalLanguages,
		portalCourses
	} 
})