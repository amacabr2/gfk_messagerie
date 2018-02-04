import Vue from 'vue'
import VueRouter from 'vue-router'
import Messagerie from './components/MessagerieComponent'
import Messages from './components/MessagesComponent'
import store from './store/store'

window.io = require('socket.io-client')

Vue.use(VueRouter)

let messagerie = document.querySelector('#messagerie')

if (messagerie) {
    const routes = [
        {path: '/'},
        {path: '/:id', component: Messages, name: 'conversations'}
    ]

    const router = new VueRouter({
        mode: 'history',
        routes,
        base: messagerie.getAttribute('data-base')
    })

    new Vue({
        el: '#messagerie',
        components: { Messagerie },
        store,
        router
    })
}

