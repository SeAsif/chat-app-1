<template>
    <div class="chat">
        <chat-messages></chat-messages>
        <form action="">
            <div class="field">
              <div class="control">
                <textarea class="textarea" placeholder="Textarea"></textarea>
                <span>Hit return to send or CTRL + Return for a new line.</span>
              </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {

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
    span {
        padding-top: 10px;
    }
</style>
