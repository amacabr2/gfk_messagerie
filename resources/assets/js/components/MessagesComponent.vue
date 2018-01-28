<template>
    <section class="card">
        <div class="card-header">
            John
        </div>
        <div class="card-body">
            <div v-for="message in messages">
                <Message :message="message" ></Message>
            </div>
        </div>
    </section>
</template>

<script>
    import Message from './MessageComponent'

    export default {
        components: {Message},
        computed: {
            messages: function () {
                return this.$store.getters.messages(this.$route.params.id)
            }
        },
        mounted() {
            this.$store.dispatch('loadConversations', this.$route.params.id)
        }
    }
</script>
