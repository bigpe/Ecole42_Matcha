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


function append_message(data){
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
    messageDiv.val(0);
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
};

socket.onerror = function (error) {
};

socket.onmessage = function (event) {
let data = JSON.parse(event.data);
if (data.user_from === user_chat_to && data.user_to === user_from && data.type === 1)
    append_message(data);
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
                    socket.send(JSON.stringify(messageJSON));
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



