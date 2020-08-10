let people_block = document.getElementById("people_block");
function unblock_user() {
    let node = this;
    this.parentNode.setAttribute("id", "remove_user");
    setTimeout(function () {
        node.parentNode.remove();
        if(!document.getElementsByClassName("people").length){
            document.getElementById("people_block").innerHTML = "<div id='system_message'><h2>Not Blocked Users</h2></div>";
        }
    }, 500);
    $.ajax({
        url: "/settings/black_list_remove",
        method: "POST",
        data: {"login": this.previousElementSibling.innerText}
    });

}

function unblock_user() {
    let node = this;
    this.parentNode.setAttribute("id", "remove_user");
    setTimeout(function () {
        node.parentNode.remove();
        if(!document.getElementsByClassName("people").length){
            document.getElementById("people_block").innerHTML = "<div id='system_message'><h2>Not Blocked Users</h2></div>";
        }
    }, 500);
    $.ajax({
        url: "/settings/black_list_remove",
        method: "POST",
        data: {"login": this.previousElementSibling.innerText}
    });
}
function hijack_user() {
    let node = this;
    let login = node.parentElement.innerText;
    window.location.href = "/admin/hijack/?login=" + login;
}
function create_users() {
    let node = this;
    let button_text = node.innerHTML;
    $.ajax({
        url: "/admin/create_users",
        method: "POST",
        beforeSend: function () {
            node.innerHTML = "<i class=\"fas fa-hourglass-half\"></i>";
        },
        complete: function (data) {
            let users = JSON.parse(data.responseText);
            load_users(users);
            node.innerHTML = button_text;
        }
    });
}
function delete_report() {
    let node = this;
    let login = node.parentElement.innerText.trim();
    let users_count = document.getElementById("users_reported_count");
    $.ajax({
        url: "/admin/delete_report",
        method: "POST",
        data: {"login": login},
        beforeSend: function () {
            users_count.innerText = (parseInt(users_count.innerText) - 1).toString();
            node.parentElement.remove();
        }
    });
}
function delete_user() {
    let node = this;
    let login = node.parentElement.innerText.trim();
    let users_count = document.getElementById("users_count");
    $.ajax({
        url: "/admin/delete_user",
        method: "POST",
        data: {"login": login},
        beforeSend: function () {
            users_count.innerText = (parseInt(users_count.innerText) - 1).toString();
            node.parentElement.remove();
        }
    });
}
function load_users(users) {
    for (let i = 0; i < users.length; i++) {
        let login = users[i]['login'];
        let people = document.createElement("div");
        people.setAttribute("class", "people");
        let people_name = document.createElement("h3");
        people_name.setAttribute("class", "people_name");
        people_name.innerHTML = "<a href='/profile/view/login=" + login + "'>" + login + "</a>";
        people.append(people_name);
        let remove = document.createElement("span");
        remove.setAttribute("class", "remove");
        remove.setAttribute("onclick", "delete_user.apply(this)");
        remove.setAttribute("title", "Remove User");
        remove.innerHTML = "<i class=\"fas fa-trash-alt\"></i>";
        people.append(remove);
        let hijack = document.createElement("span");
        hijack.setAttribute("class", "hijack");
        hijack.setAttribute("onclick", "hijack_user.apply(this)");
        hijack.setAttribute("title", "Sign in Into");
        hijack.innerHTML = "<i class=\"fas fa-sign-in-alt\"></i>";
        people.append(hijack);
        people_block.append(people);
    }
}
function get_all_users() {
    let node = this;
    $.ajax({
        url: "/admin/get_all_users",
        method: "POST",
        complete: function (data) {
            let users = JSON.parse(data.responseText);
            load_users(users);
            node.remove();
        }
    });
}