
function append_message(data){
    let user_from = $('#my_login').val();
    let messageDiv = document.createElement("div");
    if (data.user_from == user_from)
        messageDiv.setAttribute("class", 'my_message');
    else{
        messageDiv.setAttribute('class', 'other_message');
        let miniPhoto = document.getElementById("mini_photo").cloneNode(true);
        messageDiv.append(miniPhoto);
    }
    let span = document.createElement("span");
    span.innerText = data.message;
    messageDiv.append(span);
    $('.messages').append(messageDiv);
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
let user_from = $('#my_login').val();
let user_to = $('#login_to').val();
if (data.user_to == user_from && data.user_from == user_to && data.type == 1)
    append_message(data);
};

function send_message() {
    let user_from = $('#my_login').val();
    let user_to = $('#login_to').val();
    let message = document.getElementById("text").value;
    let messageJSON = {
        user_from: user_from,
        user_to: user_to,
        message: message,
        type: 1
    };
    socket.send(JSON.stringify(messageJSON));
    append_message(messageJSON);
     $.ajax({
        url: "/conversation/save_message",
        method: "POST",
        data: {"user_from": user_from,
        "user_to": user_to,
        "message": message,
        "type": 1}, //type 1  - личное сообщение
        success: function () {

        }});
    document.getElementById("text").value = '';
}

document.getElementById('text').addEventListener('keydown', function (k){
    if (k.keyCode == 13){
        send_message();
        document.getElementById("text").value = '';
    }
});



