window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
    require('bootstrap-sass');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    //auth:{ headers: { 'Authorization': 'Bearer ' + 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvdGVzdGUtbGFyYXZlbC5iaWtlcnNlY29kZWxpdmVyeS5jb20uYnJcL2FwaVwvbG9naW4iLCJpYXQiOjE1NzA3NDcxNzAsImV4cCI6MTU3MTM1MTk3MCwibmJmIjoxNTcwNzQ3MTcwLCJqdGkiOiJ0YWFydzdXdmtOVk5EYXJaIiwic3ViIjozLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.aKfVXSzCEtclggGNiolCQ0ierci8TtObGfxj6MSoSZE' } },
    broadcaster: 'pusher',
    key: 'anyKey',
    wsHost:  window.location.hostname,
    wsPort: 6001,
    //wssPort: 6001,
    //disableStats: true,
    encrypted: false,
    //enabledTransports: ['ws', 'wss']
});
/*
window.Echo.channel('solicitacoes')
.listen('NovaSolicitacao', (e) => {
    console.log(e)
})
/*
window.Echo.join('solicitacoes') 
    .listen('NovaSolicitacao', (event) =>{
        console.log(event);
    })*/