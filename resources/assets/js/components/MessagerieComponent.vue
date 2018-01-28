<template>
   <section class="row">
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
            this.loadConverstion()
        },
        watch: {
           '$route.params.id': function () {
               this.loadConverstion()
           }
        },
        methods: {
            loadConverstion() {
                this.$store.dispatch('loadMessages', this.$route.params.id)
            }
        }
    }
</script>