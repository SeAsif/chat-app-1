<template>
    <div class="chat__message chat__message--own">
        <strong class="chat__message-user">Mpilo</strong>
        <span class="chat__message-timestamp">12 min ago.</span>
        <p class="chat__message-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    </div>
</template>

<script>
    export default {

    }
</script>

<style lang="scss">
    .chat {
        &__message {
            padding: 15px;
            border-bottom: 1px solid #eee;
            &--own {
                background-color: #f0f0f0f;
            }
            &-user {
                font-weight: 800;
            }
            &-timestamp {
                color: #aaa;
            }
            &-body {
                margin-bottom: 0;
                white-space: pre-wrap;
            }
        }
    }
</style>
