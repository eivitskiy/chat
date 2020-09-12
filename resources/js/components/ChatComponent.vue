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
                                    <span class="badge badge-secondary">{{ message.user.username }}</span>:
                                    {{ message.message }}
                                </li>

                            </ul>
                        </div>

                        <div id="message-wrapper" class="input-group position-absolute row col-12 mr-5 mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Your message:</span>
                            </div>
                            <input id="message-input" aria-label="Message" class="form-control" type="text">
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
                            <li class="list-group-item">Cras justo odio</li>
                            <li class="list-group-item">Dapibus ac facilisis in</li>
                            <li class="list-group-item">Morbi leo risus</li>
                            <li class="list-group-item">Porta ac consectetur ac</li>
                            <li class="list-group-item">Vestibulum at eros</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:   {
        messages: {
            type:     Array,
            required: true,
        }
    },
    data() {
        return {
            modelMessages: this.messages
        };
    },
    methods: {
        sendMessage() {
            let chatWrapper  = $('#chat-wrapper');
            let messageInput = $('#message-input');

            this.modelMessages.push({
                created_at: (new Date()).toLocaleString(),
                user:       {
                    username: 'John'
                },
                message:    messageInput.val()
            });

            setTimeout(function () {
                chatWrapper.scrollTop(chatWrapper[0].scrollHeight);
                messageInput.val(null);
            }, 100);
        }
    },
    mounted() {
        console.log(this.modelMessages);
    }
};
</script>

<style>
.card {
    max-height: 50vh;
}

#chat-wrapper {
    height: 95% !important;
}

#message-wrapper {
    bottom: 0;
    padding: 0 15px;
    max-width: 99%;
}
</style>
