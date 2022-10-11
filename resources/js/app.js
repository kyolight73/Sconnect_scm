require('./bootstrap');

Echo.private('chat.')
    .listen('NotificationEvent', (e) => {
        this.messages.push({
            message: e.message.message,
            user: e.user
        });
    });
