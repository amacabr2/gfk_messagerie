<template>
    <div>
        <div class="row">
            <div :class="cls">
                <p>
                    <strong>{{  message.from.name }}</strong><br>
                    {{ message.content }}
                </p>
            </div>
        </div>
        <hr>
    </div>
</template>

<script>
    export default {
        props: {
            message: Object,
            user: Number
        },
        computed: {
            cls() {
                let cls = ['col-md-10']
                if (this.message.from_id === this.user) {
                    cls.push('offset-md-2 text-right')
                }
                return cls
            }
        }
    }
</script>