import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router';
import { Quasar } from 'quasar';
import App from './App.vue';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import { Notify } from 'quasar';
import Chat from './components/Chat.vue';

import 'quasar/src/css/index.sass'; 
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, 
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true,
    forceTLS: true,
    wsHost: 'ws-ap2.pusher.com', 
    wsPort: 443,
    disableStats: true,
    authEndpoint: '/api/broadcasting/auth',
    verify:false,
    auth: {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('authToken')}`, 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    },
});
// window.Echo.connector.pusher.connection.bind('error', (error) => {
//     console.log('Pusher connection error:', error);
// });

window.Echo.connector.pusher.connection.bind('connected', (data) => {
    console.log('Pusher connected:', data);
});
console.log(document.querySelector('meta[name="csrf-token"]').getAttribute('content'),"csrftokennnnn");


const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/chat', component: Chat },
    { path: '/:pathMatch(.*)*', redirect: '/login' },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

const app = createApp(App);

app.use(Quasar, {
    plugins: {Notify}, 
    config: {
        framework: {
            iconSet: 'material-icons', 
        },
    },
});


app.use(router);

app.mount('#app');
