<template>
    <section class="card">
        <div class="card-header">
            <h3>{{ name }}</h3>
        </div>

        <div class="card-body messagerie_body">
            <div v-for="message in messages">
                <Message :message="message" :key="message.id" :user="user"></Message>
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
            </form>

            <div class="messagerie_loading" v-if="loading">
                <div class="loader"></div>
            </div>
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
            },
            lastMessage: function () {
               return this.messages[this.messages.length - 1]
            },
            name: function () {
                return this.$store.getters.conversation(this.$route.params.id).name
            },
            count: function () {
                return this.$store.getters.conversation(this.$route.params.id).count
            }
        },
        mounted() {
            this.loadConverstion()
            this.messagesBody = this.$el.querySelector('.messagerie_body')

            document.addEventListener('visibilitychange', this.onVisible)
        },
        destroyed() {
            document.removeEventListener('visibilitychange', this.onVisible)
        },
        watch: {
            '$route.params.id': function () {
                this.loadConverstion()
            },
            lastMessage: function () {
                this.scrollBot()
            }
        },
        methods: {
            async loadConverstion() {
                await this.$store.dispatch('loadMessages', this.$route.params.id)
                if (this.messages.length < this.count) {
                    this.messagesBody.addEventListener('scroll', this.onScroll)
                }
            },
            scrollBot() {
                this.$nextTick(_ => {
                    this.messagesBody.scrollTop = this.messagesBody.scrollHeight
                })
            },
            async onScroll() {
                if (this.messagesBody.scrollTop === 0) {
                    this.loading = true
                    this.messagesBody.removeEventListener('scroll', this.onScroll)
                    let previousHeight = this.messagesBody.scrollHeight
                    await this.$store.dispatch('loadPreviousMessages', this.$route.params.id)
                    this.$nextTick(_ => {
                        this.messagesBody.scrollTop = this.messagesBody.scrollHeight - previousHeight
                    })
                    if (this.messages.length < this.count) {
                        this.messagesBody.addEventListener('scroll', this.onScroll)
                    }
                    this.loading = false
                }
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
            },
            onVisible() {
                if (document.hidden === false) {
                    this.$store.dispatch('loadMessages', this.$route.params.id)
                }
            }
        }
    }
</script>
