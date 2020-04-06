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

let address = window.location.pathname;

let socketNotif = new WebSocket("ws://" + domain + ":6969/notification_ws/notification.php");
console.log(window.document.domain);

socketNotif.onopen = function () {
    let cookie = document.cookie.split('=', 2)[1];
    let messageJSON = {};
    if (get[0] === "login" && address === "/profile/view/" && get[1] !== "") {
        messageJSON = {
            user_from: cookie,
            user_to: get[1],
            type: 1
        };
        socketNotif.send(JSON.stringify(messageJSON));
        }
};

socketNotif.onclose = function () {
    setTimeout(function() {
        socketNotif = new WebSocket("ws://" + domain + ":6969/ws/server.php");
    }, 1000);
};

socketNotif.onerror = function (error) {
};

socketNotif.onmessage = function (event) {
    let data = JSON.parse(event.data);
    let cookie = document.cookie.split('=', 2)[1];
    if (data.type === 11 && get[1] != data.chat_id){
        for(let user in data.user_to){
            if (data.user_to[user]['session_name'] === cookie ){
                    appendNotifications("new message " + data.user_from + "\nmassage: " + data.message);
                    appendCountNewMessage();
                }
            }}
    if (data.type === 1)
        for(let user in data.user_to)
            if (data.user_to[user]['session_name'] === cookie ){
                appendNotifications("new visit " + data.user_from);
                appendCountNotifications();
            }
    if (data.type === 2)
        for(let user in data.user_to)
            if (data.user_to[user]['session_name'] === cookie ){
                appendNotifications("new like " + data.user_from);
                appendCountNotifications();
            }
    if (data.type === 3)
        for(let user in data.user_to)
            if (data.user_to[user]['session_name'] === cookie ){
                appendNotifications("new dislike " + data.user_from);
                appendCountNotifications();
            }


};


function appendNotifications(data) {
    let notificationBlock = document.getElementById("notification_block");
    div = document.createElement("div");
    div.setAttribute("id", "notif_content");
    div.innerText = data;
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



