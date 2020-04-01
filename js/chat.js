let params = window
    .location
    .search
    .replace('?', '')
    .split('&')
    .reduce(
        function (p, e) {
            let a = e.split('=');
            p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        },
        {}
    );
let user_chat_to = params['id'];
let user_from = document.cookie.split('=', 2)[1];


function append_message(data) {
    let messages = $.parseJSON(data);
    console.log(messages);
    for(let i in messages){
        if (messages[i]['message_from'].localeCompare("my") === 0)
            append_my_old_message(messages[i]);
        else
            append_other_old_message(messages[i]);
    }
}

function  append_my_old_message(data) {
    let messageDiv = document.createElement("div");
    messageDiv.setAttribute("class", 'my_message');
    messageDiv.setAttribute('title', data['creation_date']);
    let span = document.createElement("span");
    span.innerText = data['text_message'];
    let i = document.createElement("i");
    if (data['status_message'] === '0')
        i.setAttribute('class', 'fas fa-check');
    else
        i.setAttribute('class', 'fas fa-check-double');
    messageDiv.append(span);
    messageDiv.append(i);
    $('.messages').prepend(messageDiv);
}

function  append_other_old_message(data) {
    let messageDiv = document.createElement("div");
    messageDiv.setAttribute('class', 'other_message');
    messageDiv.setAttribute('title', data['creation_date']);
    let photo = document.getElementById("mini_photo");
    if (photo !== null ){
        let miniPhoto = photo.cloneNode(true);
        messageDiv.append(miniPhoto);
    }
    let span = document.createElement("span");
    span.innerText = data['text_message'];
    let i = document.createElement("i");
    if (data['status_message'] === '0')
        i.setAttribute('class', 'fas fa-check');
    else
        i.setAttribute('class', 'fas fa-check-double');
    messageDiv.append(i);
    messageDiv.append(span);
    $('.messages').prepend(messageDiv);
}

function append_other_message(data){
    let messageDiv = document.createElement("div");
    messageDiv.setAttribute('class', 'other_message');
    let photo = document.getElementById("mini_photo");
    if (photo !== null ){
       let miniPhoto = photo.cloneNode(true);
        messageDiv.append(miniPhoto);
    }
    let span = document.createElement("span");
    span.innerText = data.message;
    messageDiv.append(span);
    $('.messages').append(messageDiv);
}
function append_my_message(data){
    let messageDiv = document.createElement("div");
    messageDiv.setAttribute("class", 'my_message');
    let span = document.createElement("span");
    span.innerText = data.message;
    messageDiv.append(span);
    $('.messages').append(messageDiv);
    messageDiv.value = '';
}
let socket = new WebSocket("ws://192.168.0.191:8888/ws/server.php");

socket.onopen = function () {
    console.log("join in Chat");
};

socket.onclose = function () {
    setTimeout(function() {

        socket = new WebSocket("ws://192.168.0.191:8888/ws/server.php");

    }, 1000);
};

socket.onerror = function (error) {
};

socket.onmessage = function (event) {
let data = JSON.parse(event.data);
if (data.user_from === user_chat_to && data.user_to === user_from && data.type === 1){
    append_other_message(data);
}
};

function send_message() {
    let message = document.getElementById("text").value;
        if (message.trim() !== ''){
         $.ajax({
            url: "/conversation/save_message",
            method: "POST",
            data: {"user_to": user_chat_to,
            "message": message,
            "type": 1   }, //type 1  - личное сообщение
            success: function (userStatus) {
                let cookie = document.cookie.split('=', 2)[1];
                let messageJSON = {
                    user_from: cookie,
                    user_to: user_chat_to,
                    message: message,
                    type: 1
                };

                if(Number(userStatus) != 0){
                    console.log(userStatus);
                    //socket.send(JSON.stringify(messageJSON));
                }
                append_my_message(messageJSON);
            }});
         document.getElementById("text").value = '';

    }
}

document.getElementById('text').addEventListener('keydown', function (k){
    if (k.keyCode === 13){
        send_message();
        document.getElementById("text").value = null;
        event.preventDefault()
    }

});
let lastMessage = 10;
document.getElementById('load_more_message').addEventListener('click', function () {
    $.ajax({
        url: '/conversation/handler_message',
        method: 'POST',
        data: {'chat_id': user_chat_to,
        'start_message':lastMessage},
        success: function (messages) {
            lastMessage += 10;
            if(messages.length > 0)
            append_message(messages);
        }
    })
});



