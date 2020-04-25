let matcha_load = document.getElementById("matcha_load");
let users_wasted = document.getElementById("users_wasted");
let block_user_option = document.getElementById("block_user");
let profile_block = document.getElementById("profile_block");
let right_arrow = document.getElementById("right_arrow");
let left_arrow = document.getElementById("left_arrow");
let photo = document.getElementsByClassName("photo")[0];
let connect_status = document.getElementById("connect_status");
let profile_filled_progress_bar = document.getElementById("profile_filled_progress_bar");
let login = document.getElementById("login");
let age = document.getElementById("age");
let sex_preference = document.getElementById("sex_preference");
let fame_rating = document.getElementById("fame_rating");
let real_name = document.getElementById("real_name");
let info = document.getElementById("info");
let geo = document.getElementById("geo");
let tags_block = document.getElementById("tags_block");
let YMapsID = document.getElementById("YMapsID");
let matched_users;
let current_user = 0;
let main_photo = 0;
let count_photo = 0;
let current_photo = 0;
let user_photos = [];
let count_users = 0;

if (deviceDetect()){
    let startPos;
    let geoOptions = {
        enableHighAccuracy: true
    };
    let geoSuccess = function(position) {
        startPos = position;
        $.ajax({
            url: "/matcha/put_geo_users",
            method: "POST",
            data: {'latitude' : startPos.coords.latitude,
            'longitude' : startPos.coords.longitude},
            complete: function () {
                load_matcha();
            }}
        )
    };
    let geoError = function(error) {
        console.log('Error occurred. Error code: ' + error.code);
    };
    navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
}


if(navigator.userAgent.match("Firefox"))
    progress_bar_css = find_pointer_for_style("progress::-moz-progress-bar");
else
    progress_bar_css = find_pointer_for_style("progress::-webkit-progress-value");

function load_matcha() {
    $.ajax({
        url: "/matcha/get_matcha_users",
        method: "POST",
        complete: function (data) {
            matched_users = JSON.parse(data.responseText);
            count_users = matched_users.length;
            fill_user_profile();
        }}
    )
}
function fill_user_profile() {
    if(matched_users[current_user]) {
        fill_user_main_photo();
        load_user_photos(matched_users[current_user]['user_photos']);
        fill_user_profile_progress_bar();
        fill_user_connect_status();
        fill_user_login();
        fill_user_age();
        fill_user_sex_preference();
        fill_user_fame_rating();
        fill_user_real_name();
        fill_user_info();
        fill_user_location();
        fill_user_tags();
        fill_user_map();
        display_profile();
    }
    else
        not_any_users();
}
function fill_user_main_photo() {
    photo.id = matched_users[current_user]['main_photo']['photo_token'];
    let photo_path = 'url("' + matched_users[current_user]['main_photo']['photo_src'] + '") no-repeat center / cover';
    let image = new Image();
    image.src = matched_users[current_user]['main_photo']['photo_src'].replace(".", "");
    image.onerror = function () {
        photo.style.background = "url('/images/placeholder.png') no-repeat center / cover";
    };
    image.onload = function () {
        image.remove();
    };
    photo.style.background = photo_path.replace(".", "");
}
function fill_user_profile_progress_bar() {
    profile_filled_progress_bar.value = matched_users[current_user]['profile_filled']['value'];
    progress_bar_value(0);
}
function load_user_photos(photo_array) {
    user_photos = [];
    for(let i = 0; i < photo_array.length; i++) {
        main_photo = 0;
        if(document.getElementById(photo_array[i]["photo_token"])) {
            current_photo = i;
            main_photo = 1;
        }
        user_photos.push({
            "photo_token" : photo_array[i]['photo_token'],
            "photo_src": photo_array[i]['photo_src'],
            "main_photo": main_photo});
    }
    count_photo = photo_array.length;
    if(count_photo > 1) {
        left_arrow.style.visibility = "visible";
        right_arrow.style.visibility = "visible";
    }
    else{
        left_arrow.style.visibility = "hidden";
        right_arrow.style.visibility = "hidden";
    }
}
function fill_user_connect_status() {
    connect_status.style.color = matched_users[current_user]['online_status']['status'];
    connect_status.setAttribute("title", matched_users[current_user]['online_status']['last_online']);
}
function fill_user_login() {
    login.innerText = matched_users[current_user]['user_login'];
}
function fill_user_age() {
    age.innerText = matched_users[current_user]['user_age'];
}
function fill_user_sex_preference() {
    sex_preference.innerHTML = matched_users[current_user]['user_sex_preference']['sex_preference_icon'] +
        '<span class="tooltiptext">' + matched_users[current_user]['user_sex_preference']['sex_preference_name'] + '</span>';
    sex_preference.style.color = matched_users[current_user]['user_sex_preference']['sex_preference_color'];
}
function fill_user_fame_rating() {
    fame_rating.innerHTML = matched_users[current_user]['user_fame_rating']['fame_rating_icon'] +
        '<span class="tooltiptext">' +  matched_users[current_user]['user_fame_rating']['fame_rating_name'] + '</span>'
    fame_rating.style.color = matched_users[current_user]['user_fame_rating']['fame_rating_color'];
}
function fill_user_real_name() {
    if(!matched_users[current_user]['user_real_name'])
        real_name.innerHTML = "<i class=\"fas fa-user-ninja\"></i> I'm Anon";
    else
        real_name.innerHTML = matched_users[current_user]['user_real_name'];
}
function fill_user_info() {
    if(!matched_users[current_user]['user_info'])
        info.innerHTML = "<i class=\"fas fa-user-ninja\"></i> I'm Very Shy";
    else
        info.innerText = matched_users[current_user]['user_info'];
}
function fill_user_location() {
    geo.innerHTML = "";
    geo.innerHTML = geo.innerHTML + matched_users[current_user]['user_geo'];
}
function fill_user_tags() {
    tags_block.innerHTML = "";
    for(let i = 0; i < matched_users[current_user]['user_tags'].length; i++) {
        let tag = document.createElement("input");
        tag.setAttribute("class", "tags");
        tag.setAttribute("id", matched_users[current_user]['user_tags'][i]['tag_name']);
        tag.setAttribute("value", matched_users[current_user]['user_tags'][i]['tag_name']);
        let tag_label = document.createElement("label");
        tag_label.setAttribute("for", matched_users[current_user]['user_tags'][i]['tag_name']);
        tag_label.setAttribute("class", "tags_labels_another");
        tag_label.setAttribute("style", "color: " +
            matched_users[current_user]['user_tags'][i]['tag_color']);
        tag_label.innerHTML = matched_users[current_user]['user_tags'][i]['tag_icon'] + " " +
            matched_users[current_user]['user_tags'][i]['tag_name'];
        tags_block.append(tag);
        tags_block.append(tag_label);
    }
}
function fill_user_map() {
    YMapsID.innerHTML = "";
    ymaps.ready(function () {
        let myMap = new ymaps.Map("YMapsID", {
            center: [matched_users[current_user]['geo']['geo_latitude'], matched_users[current_user]['geo']['geo_longitude']],
            zoom: 15
        });
        let myPlacemark = new ymaps.Placemark([matched_users[current_user]['geo']['geo_latitude'], matched_users[current_user]['geo']['geo_longitude']]);
        myMap.geoObjects.add(myPlacemark);
    });
}
function display_profile() {
    matcha_load.style.display = "none";
    profile_block.style.display = "inherit";
}
function hide_profile() {
    matcha_load.style.display = "inherit";
    profile_block.style.display = "none";
}
function match_user() {
    like();
    next_user();
}
function miss_user() {
    next_user();
}
function next_user() {
    user_matched();
    current_user += 1;
    if(count_users == current_user)
        not_any_users();
    else {
        hide_profile();
        fill_user_profile();
        display_profile();
    }
}
function user_matched(){
    $.ajax({
        url: "Matcha/user_matched",
        method: "POST",
        data: {"login" : matched_users[current_user]['user_login']}
    });
}
function photo_forward(){
    if(count_photo > 1) {
        photo = document.getElementsByClassName("photo")[0];
        current_photo += 1;
        if (current_photo >= count_photo)
            current_photo = 0;
        change_current_photo();
    }
}
function photo_backward() {
    if(count_photo > 1) {
        photo = document.getElementsByClassName("photo")[0];
        current_photo -= 1;
        if (current_photo < 0)
            current_photo = count_photo - 1;
        change_current_photo();
    }
}
function change_current_photo() {
    photo.id = user_photos[current_photo]['photo_token'];
    let photo_path = 'url("' + user_photos[current_photo]['photo_src'] + '")';
    let image = new Image();
    image.src = user_photos[current_photo]['photo_src'].replace(".", "");
    image.onerror = function(){
        photo.style.backgroundImage = "url('/images/placeholder.png')";
    };
    image.onload = function () {
        image.remove();
    };
    photo.style.backgroundImage = photo_path.replace(".", "");
}
function block_user() {
    profile_save_settings(10, matched_users[current_user]['user_login']); //Type - 10 Block User
    block_user_option.innerHTML = "<i class=\"fas fa-lock-open\"></i>";
    block_user_option.setAttribute("onclick", "unblock_user()");
}
function unblock_user() {
    profile_save_settings(11, matched_users[current_user]['user_login']); //Type - 11 Unblock User
    block_user_option.innerHTML = "<i class=\"fas fa-user-lock\"></i>";
    block_user_option.setAttribute("onclick", "block_user()");
}
function profile_save_settings(setting_type, setting_value) {
    $.ajax({
        url: "/profile/save_settings",
        method: "POST",
        data: {"settings": {setting_type, setting_value}}
    })
}
function like () {
    let cookie = document.cookie.split('=', 2)[1];
    let messageJSON = {};
    messageJSON = {
        user_from: cookie,
        user_to: matched_users[current_user]['user_login'],
        type: 2
    };
    socketNotif.send(JSON.stringify(messageJSON));
    $.ajax({
        url: "/profile/like",
        method: "POST",
        data: {"login": matched_users[current_user]['user_login']}
    })
}
function not_any_users() {
    profile_block.style.display = "none";
    matcha_load.style.display = "none";
    users_wasted.style.display = "grid";
}