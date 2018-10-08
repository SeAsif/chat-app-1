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
    import Bus from '../../bus'
    import moment from 'moment'
    export default {
        data () {
            return {
                body: null,
                bodyBackedUp: null
            }
        },
        methods : {
            handleMessageInput (e) {
                this.bodyBackedUp = this.body// if request fails clone remains
                if (e.keyCode === 13 && !e.shiftKey) {
                    e.preventDefault()
                    this.send()
                }
            },
            buildTempMessage () {
                let tempId = Date.now();
                return {
                    id: tempId,
                    body: this.body,
                    created_at: moment().utc(0).format('YYYY-MM-DD HH:mm:ss'),
                    selfOwned: true,
                    user: {
                        name: Laravel.user.name
                    }
                }
            },
            send () {
                // send ajax request and then update ui but we'll build up a temp message then go ahead and update the ui then send a request to the backend if it fails then revert the change to the ui
                // no empty data
                if (!this.body || this.body.trim() === '') {
                    return 
                }
                let tempMessage = this.buildTempMessage();
                Bus.$emit('message.added', tempMessage)
                axios.post('/chat/messages', {//check via network tab
                    body: this.body.trim()// remove left and right hand whitespaces
                }).catch( () => {//no need for success as we're sending a temp message to the ui
                    this.body = this.bodyBackedUp//if it fails return clone of intended message
                    Bus.$emit('message.removed', tempMessage)
                })
                this.body = null
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
