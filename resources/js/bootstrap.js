window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

let pusherKey = ''
let authEndpoint = ''

if (process.env.NODE_ENV === 'production') {
    pusherKey = '527ffffc14a075ceaf06'
    authEndpoint = 'http://msc-educ-smc.cmc.ec.gc.ca/tcdd-website/broadcasting/auth'
} else if (process.env.NODE_ENV === 'development') {
    pusherKey = 'd5dc29f415486ec427d3'
    authEndpoint = 'http://localhost:8000/broadcasting/auth'
}

window.Echo = new Echo({
    authEndpoint,
    broadcaster: 'pusher',
    key: pusherKey,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: process.env.MIX_PUSHER_APP_TLS
});
