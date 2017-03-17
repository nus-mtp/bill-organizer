/**
 *
 * this is where we configure our global dependencies (libraries),
 * the contents of this file will be loaded before app.js
 */

window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
require('./semantic')
require('semantic-ui-calendar/dist/calendar')
window.axios = require('axios')
window.axios.defaults.headers.common = {
  'X-CSRF-TOKEN': window.Laravel.csrfToken,
  'X-Requested-With': 'XMLHttpRequest'
}
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
})


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
