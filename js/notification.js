let domain = window.document.domain;
let get = window
    .location
    .search
    .replace('?', '')
    .split('&')
    .reduce(
        function (p, e) {
            let a = e.split('=');
            //p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return a;}, {});
let cookie = document.cookie.split('=', 2)[1];;
let address = window.location.pathname;
let socket = new WebSocket("wss://matcha.fun:6969");
let messageJSON = {};
socket.onopen = function () {
     if (get[0] === "login" && address === "/profile/view/" && get[1] !== "") {
        messageJSON = {
            user_from: cookie,
            user_to: get[1],
            message: 'New visit :',
            type: 1
        };
        socket.send(JSON.stringify(messageJSON));
     }
    if (get[0] === "id" && address === "/conversation/chat_view/" && get[1] !== "") {
        messageJSON = {
            user_from: cookie,
            user_to: get[1],
            message: '',
            type: 19
        };
        socket.send(JSON.stringify(messageJSON));
    }
};

socket.onerror = function (error) {
};

socket.onmessage = function (event) {
    let data = JSON.parse(event.data);
    if (data.type === 13 && get[1] !== data.chat_id){
        for(let user in data.user_to){
            if (data.user_to[user]['session_name'] === cookie ){
                appendNotifications("New message from <a href='/conversation/chat_view/?id="+ data.chat_id +"'>" +
                    data.user_from + "</a>\nmassage: " +
                    data.message);
                appendCountNewMessage();
                soundClick();
                }
            }}
    if (data.type === 2 || data.type === 3 || data.type === 1 || data.type === 4)
        for(let user in data.user_to)
            if (data.user_to[user]['session_name'] === cookie ){
                appendNotifications(data.message + " <a href='/profile/view/?login=" + data.user_from + "'>"+data.user_from+"</a>");
                appendCountNotifications();
                soundClick();
            }
};

function soundClick() {
    let audio = new Audio();
    audio.src = '/../03087.mp3';
    audio.autoplay = true;
}

function appendNotifications(data) {
    let notificationBlock = document.getElementById("notification_block");
    div = document.createElement("div");
    div.setAttribute("id", "notif_content");
    div.innerHTML = data;
    notificationBlock.append(div);
    notificationBlock.style.visibility = "visible";
}

function closeNotifications() {
    $("#notif_content").remove();
    let notificationBlock = document.getElementById("notification_block");
    notificationBlock.style.visibility = "hidden";
}

function appendCountNewMessage() {
    let newMessage = document.getElementById("notification_mess");
    if (!newMessage.innerText)
        newMessage.innerText = "1";
    else{
        newMessage.innerText = Number(newMessage.innerText) + 1 ;
    }
}

function appendCountNotifications() {
    let newMessage = document.getElementById("notification_count");
    if (!newMessage.innerText)
        newMessage.innerText = "1";
    else{
        newMessage.innerText = Number(newMessage.innerText) + 1 ;
    }
}


window.addEventListener('beforeunload', function (e) {
    socket.close();
});