require('./bootstrap')

import 'core-js/es/object/assign'
import 'core-js/es/symbol/iterator'

import './helpers/interceptors'

window.Vue = require('vue')
window.events = new Vue()

import { 
    Button,
    Table,
    Input,
    Icon,
    Field,
    Toast,
    Select,
    Numberinput,
    Tabs,
    Modal,
    Message,
    Dialog,
    Checkbox
} from 'buefy'
import 'buefy/dist/buefy.css'

Vue.use(Button)
Vue.use(Table)
Vue.use(Input)
Vue.use(Field)
Vue.use(Toast)
Vue.use(Select)
Vue.use(Numberinput)
Vue.use(Tabs)
Vue.use(Modal)
Vue.use(Message)
Vue.use(Dialog)
Vue.use(Icon)
Vue.use(Checkbox)

import getUrlBase from './mixins/urlBase'
import authUser from './mixins/authUser'
import error from './mixins/errors'
import currentLang from './mixins/currentLang'
import shuffleArray from './mixins/shuffleArray'
import trans from './mixins/trans'

Vue.mixin(getUrlBase)
Vue.mixin(authUser)
Vue.mixin(error)
Vue.mixin(currentLang)
Vue.mixin(shuffleArray)
Vue.mixin(trans)

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
import Vue from 'vue'

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    store
})
