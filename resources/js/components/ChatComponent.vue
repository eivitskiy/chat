<template>
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header">Chat</div>

                    <div class="card-body position-relative">
                        <div id="chat-wrapper" class="overflow-auto">
                            <ul class="list-group">
                                <li v-for="message in messages" v-model="modelMessages" class="list-group-item">
                                    <span class="badge badge-pill">[{{ message.created_at }}]</span>
                                    <span :class="message.class" class="badge">
                                        {{ message.user.name }}
                                    </span>:
                                    {{ message.message }}
                                </li>

                            </ul>
                        </div>

                        <div id="message-wrapper" class="input-group position-absolute row col-12 mr-5 mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Your message:</span>
                            </div>
                            <input id="message-input" v-model="modelInput" aria-label="Message" class="form-control"
                                   type="text" v-on:keyup="sendMessage">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" v-on:click="sendMessage">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">Users</div>

                    <div class="card-body overflow-auto">
                        <ul class="list-group list-group-flush">
                            <li v-for="user in users"
                                v-model="modelUsers"
                                v-bind:class="{'list-group-item': true, 'bg-light': (user.name === myselfuser.name)}"
                            >{{ user.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Echo from 'laravel-echo';

export default {
    props:   {
        messages:   {
            type:     Array,
            required: true,
        },
        myselfuser: {
            type:     Object,
            required: true,
        },
        users:      {
            type:     Array,
            required: false,
            default:  function () { return []; },
        }
    },
    data() {
        return {
            modelMessages: this.messages,
            modelUsers:    this.users,
            modelInput:    '',
        };
    },
    methods: {
        appendMessage(message) {
            let chatWrapper  = $('#chat-wrapper');
            let messageInput = $('#message-input');

            this.modelMessages.push(message);

            setTimeout(function () {
                chatWrapper.scrollTop(chatWrapper[0].scrollHeight);
                messageInput.val(null);
            }, 100);
        },
        eventHere(users) {
            users.forEach((user) => {
                this.modelUsers.push(user);
            });
        },
        eventJoining(user) {
            this.modelUsers.push(user);

            this.appendMessage({
                class:      'badge-dark',
                created_at: (new Date()).toLocaleString(),
                user:       {
                    name: 'service'
                },
                message:    `user '${user.name}' joined the chat`
            });
        },
        eventLeaving(user) {
            let self = this;

            self.modelUsers.forEach((userElement, userNumber) => {
                if (user.name === userElement.name) {
                    self.modelUsers.splice(userNumber, 1);
                }
            });

            this.appendMessage({
                class:      'badge-dark',
                created_at: (new Date()).toLocaleString(),
                user:       {
                    name: 'service'
                },
                message:    `user '${user.name}' left the chat`
            });
        },
        eventNewMessage(e) {
            let msg = e.message;
            this.appendMessage(msg);
        },
        sendMessage(e) {
            if (e instanceof KeyboardEvent && e.keyCode !== 13) {
                return 0;
            }

            let self = this;

            if ('' !== self.modelInput.toString()) {
                axios.post('/chat/message', {
                    message: self.modelInput
                })
                    .then(function (response) {
                        self.appendMessage(response.data);
                        self.modelInput = '';
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            }
        },
    },
    mounted() {
        let self = this;

        window.io = require('socket.io-client');

        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host:        window.location.hostname + ':6001'
        });

        window.Echo.join('chat')
            .here(self.eventHere)
            .joining(self.eventJoining)
            .leaving(self.eventLeaving)
            .listen('.NewMessage', self.eventNewMessage);
    }
};
</script>

<style>
.mt-3.h-50 .card {
    max-height: 50vh;
    min-height: 50vh;
}

#chat-wrapper {
    height: 35vh !important;
}

#message-wrapper {
    bottom: 0;
    padding: 0 15px;
    max-width: 99%;
}
</style>
