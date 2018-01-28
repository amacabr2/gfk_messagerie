import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const get = async function (url) {
    let response = await fetch(url, {
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })

    if (response.ok) {
        return response.json()
    } else {
        let error = await response.json()
        throw new Error(error)
    }
}

export default new Vuex.Store({
    strict: true,
    state: {
        conversations: {}
    },
    getters: {
       conversations: function (state) {
           return state.conversations
       },
       messages: function (state) {
           return function (id) {
               let conversation = state.conversations[id]
               if (conversation && conversation.messages) {
                   return conversation.messages
               } else {
                   return []
               }
           }
       }
    },
    mutations: {
        addConversations: function (state, {conversations}) {
            conversations.forEach(function (c) {
                let conversation = state.conversations[c.id] || {}
                conversation = {...conversation, ...c}
                state.conversations = {...state.conversations, ...{[c.id]: conversation}}
            })
        },
        addMessages: function (state, {messages, id}) {
            let conversation = state.conversations[id] || {}
            conversation.messages = messages
            state.conversations = {...state.conversations, ...{[id]: conversation}}
        }
    },
    actions: {
        loadConversations: async function (context) {
            let response = await get('/api/conversations')
            context.commit('addConversations', {conversations: response.conversations})
        },
        loadMessages: async function(context, conversationId) {
            let response = await get('/api/conversations/' + conversationId)
            console.log(response)
            context.commit('addMessages', {messages: response.messages, id: conversationId})
        }
    }
})