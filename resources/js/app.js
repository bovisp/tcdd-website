require('./bootstrap')

import 'core-js/es/object/assign'
import 'core-js/es/symbol/iterator'

import './helpers/interceptors'

window.Vue = require('vue')
window.events = new Vue()

import getUrlBase from './mixins/urlBase'
import authUser from './mixins/authUser'
import error from './mixins/errors'

Vue.mixin(getUrlBase)
Vue.mixin(authUser)
Vue.mixin(error)

import store from './store'

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    store
})
