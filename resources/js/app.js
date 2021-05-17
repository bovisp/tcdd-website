require('./bootstrap')

import 'core-js/es/object/assign'
import 'core-js/es/symbol/iterator'

import './helpers/interceptors'

window.Vue = require('vue')
window.events = new Vue()

import getUrlBase from './mixins/urlBase'
import authUser from './mixins/authUser'
import error from './mixins/errors'
import currentLang from './mixins/currentLang'
import shuffleArray from './mixins/shuffleArray'

/**
 * Gives Vue access to the Laravel translation strings on a per language basis.
 * 
 * @param  {string} key The translation string key.
 * 
 * @return {string}     The translation string value.
 */
 Vue.prototype.trans = (key) => {
	let paths = key.split('.'),
	    current = window.trans,
      i;

	for (i = 0; i < paths.length; ++i) {
		if (current[paths[i]] == undefined) {
			return undefined;
		} else {
			current = current[paths[i]];
		}
	}
	
	return current;
}

Vue.mixin(getUrlBase)
Vue.mixin(authUser)
Vue.mixin(error)
Vue.mixin(currentLang)
Vue.mixin(shuffleArray)

import Toasted from 'vue-toasted'
Vue.use(Toasted, {
    duration: 3000,
    position: 'top-center'
})

import VueScrollTo from 'vue-scrollto'
Vue.use(VueScrollTo)

window.events.$on('errors-general', error => {
    Vue.toasted.error(error)
})

import store from './store'

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    store
})
