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
let user_chat_to = params['id'];
let user_from = document.cookie.split('=', 2)[1];
let block_user_option = document.getElementById("block_user");
let add_to_profile_block = document.getElementById("add_to_profile_block");


function append_message(data) {
    let messages = $.parseJSON(data);
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
    messageDiv.setAttribute("class", 'my_message message_send');
    let span = document.createElement("span");
    let i = document.createElement("i");
    i.setAttribute('class', 'fas fa-check');
    i.style.color ='white';
    span.innerText = data.message;
    messageDiv.append(span);
    messageDiv.append(i);
    $('.messages').append(messageDiv);
}
let socket = new WebSocket("ws://" + domain + ":8888");

socket.onopen = function () {
    let cookie = document.cookie.split('=', 2)[1];
    let messageJSON = {
        user_from: cookie,
        user_to: user_chat_to,
        type: 18
    };
    socket.send(JSON.stringify(messageJSON));
};

// socket.onclose = function () {
//     setTimeout(function() {
//         socket = new WebSocket("ws://" + domain + ":8888/ws/server.php");
//     }, 1000);
// };

socket.onerror = function (error) {
};
$(window).on('beforeunload', function(){
    socket.close();
});

socket.onmessage = function (event) {
let data = JSON.parse(event.data);
if (data.user_from === user_chat_to &&  data.type === 11){
    for(let user in data.user_to){
        if (data.user_to[user]['session_name'] === user_from ){
            append_other_message(data);
            $.ajax({
                url: "/conversation/change_message_status",
                method: "POST",
                data: {"chat_to": user_chat_to,
                    "type": 2   },
            });
            let cookie = document.cookie.split('=', 2)[1];
            let messageJSON = {
                user_from: cookie,
                user_to: user_chat_to,
                type: 19
            };
            socket.send(JSON.stringify(messageJSON));
}}}
if (data.user_from === user_chat_to && data.type === 19)
    for(let user in data.user_to)
        if (data.user_to[user]['session_name'] === user_from ){
            change_message_status();
            $.ajax({
                url: "/conversation/change_message_status",
                method: "POST",
                data: {"chat_to": user_chat_to,
                    "type": 2   },
            });
            }
if (data.user_from === user_chat_to && data.type === 18)
    for(let user in data.user_to)
        if (data.user_to[user]['session_name'] === user_from ){
            change_message_status();
            $.ajax({
                url: "/conversation/change_message_status",
                method: "POST",
                data: {"chat_to": user_chat_to,
                    "type": 2   },
            });
            }
};

function change_message_status(){
    let elements = document.getElementsByClassName("fas fa-check");
    for (i = 0, len = elements.length; i < len; i++) {
        elements[i].style.color = '#2C81B7';
    }
}

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
                    type: 11
                };
                if(Number(userStatus) !== 0){
                    socket.send(JSON.stringify(messageJSON));
                    let messageNOTIF = {
                    user_from: cookie,
                    user_to: get[1],
                    message: message,
                    type: 11
                    };
                    socketNotif.send(JSON.stringify(messageNOTIF));
                }
                append_my_message(messageJSON)
            }})
         }
        document.getElementById("text").value = '';

}

document.getElementById('text').addEventListener('keydown', function (k){
    if (k.keyCode === 13){
        send_message();
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
            if($.parseJSON(messages).length === 0){
                let more_massage_butom = document.getElementById('load_more_message');
            more_massage_butom.innerText = "No more messages";
            more_massage_butom.setAttribute("disabled", "true");
            }
        }
    })
});

function block_user() {
    block_user_option.innerHTML = "<i class=\"fas fa-lock-open\"></i>";
    block_user_option.setAttribute("onclick", "unblock_user()");
}
function unblock_user() {
    block_user_option.innerHTML = "<i class=\"fas fa-user-lock\"></i>";
    block_user_option.setAttribute("onclick", "block_user()");
}

function dislike_user() {

}
function report_user() {

}
