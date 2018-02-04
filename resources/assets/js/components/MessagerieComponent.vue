<template>
   <section class="row" style="margin-top: 4em;">
       <div class="col-md-3">
           <div class="list-group">
                <template v-for="conversation in conversations">
                    <router-link :to="{name: 'conversations', params: {id: conversation.id}}" class="list-group-item d-flex justify-content-between align-items-center">
                        {{ conversation.name }}
                        <span class="badge badge-pill badge-primary" v-if="conversation.unread">{{ conversation.unread }}</span>
                    </router-link>
                </template>
           </div>
       </div>

       <div class="col-md-9">
           <router-view></router-view>
       </div>
   </section>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        props: {
            user: Number
        },
        computed: {
            ...mapGetters(['conversations'])
        },
        mounted() {
            this.$store.dispatch('loadConversations', this.$route.params.id)
            this.$store.commit('setUser', this.user)
        }
    }
</script>