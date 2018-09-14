<template>
    <div class="chat__messages">
        <chat-message></chat-message>
    </div>
</template>

<script>
    export default {

    }
</script>

<style lang="scss">
    .chat {
        &__messages {
            height: 400px;
            max-height: 400px;
            overflow-y: scroll;
        }
    }
</style>
