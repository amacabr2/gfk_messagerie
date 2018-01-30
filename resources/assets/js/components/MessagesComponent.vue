<template>
    <section class="card">
        <div class="card-header">
            John
        </div>
        <div class="card-body">
            <div v-for="message in messages">
                <Message :message="message" :user="user"></Message>
            </div>

            <form action="" method="post" class="messagerie_form">
                <div class="form-group">
                    <textarea name="content" id="content"
                              :class="{'form-control' : true, 'is-invalid': errors['content']}" v-model="content"
                              placeholder="Ecrivez votre message" @keypress.enter="sendMessage"></textarea>
                    <div class="invalid-feedback" v-if="errors['content']">{{ errors['content'].join() }}</div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" @click="sendMessage">Envoyer</button>
                </div>
                <div class="messagerie_loading" v-if="loading">
                    <div class="loader"></div>
                </div>
            </form>
        </div>
    </section>
</template>

<script>
    import Message from './MessageComponent'
    import {mapGetters} from 'vuex'

    export default {
        components: {Message},
        data() {
            return {
                content: '',
                errors: {},
                loading: false
            }
        },
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
            },
            async sendMessage(e) {
                if (event.target.tagName === "BUTTON") {
                    e.preventDefault()
                }

                if (e.shiftKey === false) {
                    this.errors = {}
                    this.loading = true
                    e.preventDefault()

                    try {
                        await this.$store.dispatch('sendMessage', {
                            content: this.content,
                            userId: this.$route.params.id
                        })
                        this.content = ""
                    } catch (e) {
                        this.errors = e.errors
                    }

                    this.loading = false
                }
            }
        }
    }
</script>
