<template>
    <section class="card">
        <div class="card-header">
            John
        </div>
        <div class="card-body">
            <div v-for="message in messages">
                <Message :message="message" :user="user"></Message>
            </div>
        </div>
    </section>
</template>

<script>
    import Message from './MessageComponent'
    import {mapGetters} from 'vuex'

    export default {
        components: {Message},
        computed: {
            ...mapGetters(['user']),
            messages: function () {
                return this.$store.getters.messages(this.$route.params.id)
            }
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
