let params = window
    .location
    .search
    .replace('?', '')
    .split('&')
    .reduce(
        function (p, e) {
            let a = e.split('=');
            p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;}, {});

let user_from = document.cookie.split('=', 2)[1];
let address = window.location.pathname;

let socket = new WebSocket("ws://192.168.0.191:6969/notification_ws/notification.php");

socket.onopen = function () {
    console.log(address);
    let cookie = document.cookie.split('=', 2)[1];
    let messageJSON = {
        user_from: cookie,

        type: 3
    };
       // socket.send(JSON.stringify(messageJSON));

};

socket.onclose = function () {
    setTimeout(function() {
        socket = new WebSocket("ws://192.168.0.191:6969/ws/server.php");
    }, 1000);
};

socket.onerror = function (error) {
};

socket.onmessage = function (event) {
    let data = JSON.parse(event.data);
    if (data.user_from === user_chat_to &&  data.type === 1){
        for(let user in data.user_to){
            if (data.user_to[user]['session_name'] === user_from ){
                append_other_message(data);
                $.ajax({
                    url: "/conversation/change_message_status",
                    method: "POST",
                    data: {"chat_to": user_chat_to,
                        "user_from": user_from,
                        "type": 2   },
                });
                let cookie = document.cookie.split('=', 2)[1];
                let messageJSON = {
                    user_from: cookie,
                    user_to: user_chat_to,
                    type: 2
                };
                //socket.send(JSON.stringify(messageJSON));
            }}}
    if (data.user_from === user_chat_to && data.type === 2)
        for(let user in data.user_to)
            if (data.user_to[user]['session_name'] === user_from ){
                change_message_status();
            }
    if (data.user_from === user_chat_to && data.type === 3)
        for(let user in data.user_to)
            if (data.user_to[user]['session_name'] === user_from ){
                change_message_status();
            }
};





