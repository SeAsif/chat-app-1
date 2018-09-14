<template>
    <div class="chat">
        <chat-messages></chat-messages>
        <form action="">
            <div class="field">
              <div class="control">
                <textarea class="textarea" placeholder="Textarea" v-model="body" @keydown="handleMessageInput"></textarea>
                <p class="help-message">Hit return to send or SHIFT + Return for a new line.</p>
              </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                body: null
            }
        },
        methods : {
            handleMessageInput (e) {
                if (e.keyCode === 13 && !e.shiftKey) {
                    e.preventDefault()
                    this.send()
                }
            },
            send () {
                console.log(this.body)
            }
        }
    }
</script>

<style>
    .chat {
        border: 1px solid #d3e0e9;
        border-radius: 3px;
    }
    form {
        padding: 10px;
    }
    .field {
        padding: 5px 10px;
    }
    .help-message {
        color: #808080;
        margin-top: 10px;
    }
</style>
