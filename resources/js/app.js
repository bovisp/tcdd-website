require('./bootstrap');

window.Vue = require('vue');

import getUrlBase from './mixins/urlBase'
import authUser from './mixins/authUser'

Vue.mixin(getUrlBase)
Vue.mixin(authUser)

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
});
