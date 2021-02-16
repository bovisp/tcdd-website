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

if (process.env.NODE_ENV === 'production') {
    pusherKey = '527ffffc14a075ceaf06'
} else if (process.env.NODE_ENV === 'development') {
    pusherKey = 'd5dc29f415486ec427d3'
}

window.Echo = new Echo({
    authEndpoint : process.env.MIX_PUSHER_APP_AUTH_ROUTE,
    broadcaster: 'pusher',
    key: pusherKey,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: process.env.MIX_PUSHER_APP_TLS
});
