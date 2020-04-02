let current_like_status = 0;
let current_photo = 0;
let count_photo;
let main_photo;
let user_photos = [];
let photo;
let info;
let geo;
let real_name;
let city_selected = false;
let main_photo_change_click = false;
let like_block = document.getElementById("like_block");
let chat_block = document.getElementById("chat_block");
let main_photo_icon = document.getElementById("main_photo_icon");
let right_arrow = document.getElementById("right_arrow");
let left_arrow = document.getElementById("left_arrow");
let tags = document.getElementById("tags_block");
let add_to_profile_block = document.getElementById("add_to_profile_block");
let profile_filled_progress_bar = document.getElementById("profile_filled_progress_bar");
let block_user_option = document.getElementById("block_user");
let current_token;
let main_photo_index = 0;
let tag_delete = false;
let new_main_photo = 0;
let progress_bar_css;
if(navigator.userAgent.match("Firefox"))
    progress_bar_css = find_pointer_for_style("progress::-moz-progress-bar");
else
    progress_bar_css = find_pointer_for_style("progress::-webkit-progress-value");
progress_bar_value(0);

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

function like () {
    if(current_like_status) {
        current_like_status = 0;
        like_block.removeAttribute("class");
        let cookie = document.cookie.split('=', 2)[1];
        messageJSON = {
            user_from: cookie,
            user_to: get[1],
            type: 3
        };
        socketNotif.send(JSON.stringify(messageJSON));
    }
    else {
        current_like_status = 1;
        like_block.setAttribute("class", "like_filled");
        let cookie = document.cookie.split('=', 2)[1];
        messageJSON = {
            user_from: cookie,
            user_to: get[1],
            type: 2
        };
        socketNotif.send(JSON.stringify(messageJSON));
    }
    $.ajax({
        url: '/profile/like',
        method: 'POST',
        data: {"login": params['login']},
        success: function (data) {
            if(data.trim()){
                let href = '/conversation/chat_view/?id=' + data;
                chat_block.setAttribute("class", "chat_available");
                chat_block.innerHTML= "<a href ='"+ href +"'>" + chat_block.innerHTML + "</a>";
            }
            else{
                chat_block.removeAttribute("class");
                chat_block.innerHTML = "<i class=\"fas fa-comment-dots\" aria-hidden=\"true\"></i>";
            }
        }
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
    photo.style.backgroundImage = photo_path.replace(".", "");
    check_main_photo();
}
function check_main_photo() {
    if(!user_photos[current_photo]['main_photo']) {
        main_photo_icon.innerHTML = "<i class=\"far fa-star\"></i>";
        main_photo_change_click = true;
    }
    else {
        main_photo_icon.innerHTML = "<i class=\"fas fa-star\"></i>";
        main_photo_change_click = false;
    }
}

function main_photo_change() {
    if(main_photo_change_click){
        main_photo_icon.innerHTML = "<i class=\"fas fa-star\"></i>";
        user_photos[main_photo_index]['main_photo'] = 0;
        main_photo_index = current_photo;
        user_photos[current_photo]['main_photo'] = 1;
        profile_save_settings(5, user_photos[current_photo]['photo_token']);
    }
}

function remove_photo() {
    main_photo = 0;
    if(count_photo > 1) {
        add_to_profile("add_photos", "Photos", "<i class='fas fa-camera'></i>", 0);
        progress_bar_value(-8);
        profile_save_settings(4, user_photos[current_photo]['photo_token']); //Type 4 - Delete Photo
        if(user_photos[current_photo]['main_photo'])
            main_photo = 1;
        count_photo -= 1;
        if(count_photo < 5)
            document.getElementById("add_photo").style.display = "grid";
        photo = document.getElementsByClassName("photo")[0];
        user_photos.splice(current_photo, 1);
        if(current_photo == count_photo)
            current_photo = 0;
        change_current_photo();
        if(count_photo == 1)
            one_photos_clear();
        check_main_photo();
    }
    else{
        add_to_profile("add_photos", "Photos", "<i class='fas fa-camera'></i>", 0);
        progress_bar_value(-8);
        count_photo -= 1;
        profile_save_settings(4, user_photos[0]['photo_token']);
        user_photos.splice(0, 1);
        zero_photos_clear();
    }
    if(main_photo){
        user_photos[current_photo]["main_photo"] = 1;
        check_main_photo();
        profile_save_settings(5, user_photos[current_photo]['photo_token']); //Type 5 - Change Main Photo
    }
}

function zero_photos_clear() {
    document.getElementsByClassName("photo")[0].style.backgroundImage = "url('/images/placeholder.png')";
    document.getElementById("remove_photo").style.display = "none";
    main_photo_icon.style.visibility = "hidden";
}
function one_photos_clear() {
    left_arrow.style.visibility = "hidden";
    right_arrow.style.visibility = "hidden";
}
function fill_photos_buttons() {
    left_arrow.style.visibility = "visible";
    right_arrow.style.visibility = "visible";
}

function block_user() {
    profile_save_settings(10, params['login']); //Type - 10 Block User
    block_user_option.innerHTML = "<i class=\"fas fa-lock-open\"></i>";
    if(current_like_status)
        like();
    like_block.removeAttribute("onclick");
    block_user_option.setAttribute("onclick", "unblock_user()");
}
function unblock_user() {
    profile_save_settings(11, params['login']); //Type - 11 Unblock User
    block_user_option.innerHTML = "<i class=\"fas fa-user-lock\"></i>";
    like_block.setAttribute("onclick", "like()");
    block_user_option.setAttribute("onclick", "block_user()");
}

function add_photo() {
    main_photo = 0;
    let file_input = document.createElement("input");
    file_input.type = "file";
    file_input.setAttribute("multiple", "multiple");
    file_input.click();
    file_input.onchange = function () {
        let file = file_input.files;
        for (let i = 0; i < file.length; i++){
            if(count_photo >= 1)
                fill_photos_buttons();
            if(count_photo == 0)
                new_main_photo = 1;
            count_photo += 1;
            if(count_photo <= 5)
                progress_bar_value(8);
            if(count_photo >= 5) {
                document.getElementById("add_photo").style.display = "none";
                if(document.getElementById("add_photos"))
                    document.getElementById("add_photos").remove();
            }
            if(count_photo > 5){
                count_photo = 5;
                break
            }
            let file_reader = new FileReader();
            file_reader.readAsDataURL(file[i]);
            file_reader.onload = function () {
                let photo_token = Date.now();
                let image_data = file_reader.result;
                if(count_photo == 1) {
                    document.getElementsByClassName("photo")[0].style.backgroundImage =
                        "url('" + image_data + "')";
                    main_photo_icon.style.visibility = "visible";
                    document.getElementById("remove_photo").style.display = "grid";
                    main_photo = 1;
                }
                if(new_main_photo == 1){
                    document.getElementsByClassName("photo")[0].style.backgroundImage =
                        "url('" + image_data + "')";
                    main_photo_icon.style.visibility = "visible";
                    document.getElementById("remove_photo").style.display = "grid";
                    main_photo = 1;
                    new_main_photo = 0;
                }
                user_photos.push({"photo_token": photo_token, "photo_src": image_data, "main_photo": main_photo});
                if(main_photo) {
                    profile_save_settings(6, {"photo_token": photo_token, "photo_base64": image_data});
                    //Type 6 - Add new Photo and Set Main Photo
                    main_photo = 0;
                }
                else
                    profile_save_settings(3,
                        {"photo_token": photo_token, "photo_base64": image_data}); //Type 3 - Add new Photo
                check_main_photo();
            }
        }
    }
}

function like_status(l) {
    current_like_status = l;
}
function load_user_photos(photo_array) {
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
}

function change_real_name() {
    real_name = document.getElementById("real_name");
    let real_name_change_accept = document.createElement("span");
    real_name_change_accept.setAttribute("id", "real_name_change_accept");
    real_name_change_accept.innerHTML = "<i class=\"fas fa-check\"></i>";
    let real_name_input = document.createElement("input");
    real_name_input.setAttribute("type", "text");
    if(real_name.innerText.match("Anon"))
        real_name_input.value = "";
    else
        real_name_input.value = real_name.innerText;
    real_name.innerText = "";
    real_name.onclick = null;
    real_name.append(real_name_input);
    real_name.append(real_name_change_accept);
    real_name_input.focus();
    real_name_input.addEventListener("keydown", function (e) {
        if(e.keyCode == 13)
            real_name_change_accept.click();
    });
    real_name_change_accept.onclick = function(){
        profile_save_settings(9, real_name_input.value.trim()); //Type 9 - Save new Real Name
        let real_name_change = document.createElement("span");
        real_name_change.setAttribute("id", "real_name_change");
        real_name_change.setAttribute("onclick", "change_real_name()");
        real_name_change.innerHTML = "<i class=\"fas fa-pencil-alt\"></i>";
        if(real_name_input.value.trim().length == 0) {
            real_name.innerHTML = "<i class=\"fas fa-user-ninja\"></i> I'm Anon";
            add_to_profile("add_full_name", "Full Name", "<i class='fas fa-user-tie'></i>", 20);
        }
        else {
            real_name.innerText = real_name_input.value.trim();
            if(document.getElementById("add_full_name")) {
                progress_bar_value(20);
                document.getElementById("add_full_name").remove();
            }
        }
        real_name.append(real_name_change);
        real_name_input.remove();
        real_name_change_accept.remove();
    };
}

function add_to_profile(id, name, icon, step) {
    if(!document.getElementById(id)) {
        add_to_profile_block.innerHTML = add_to_profile_block.innerHTML.trim();
        let add_to_profile = document.createElement("span");
        add_to_profile.setAttribute("id", id);
        add_to_profile.setAttribute("title", "Add " + name + " to Fulfil your profile");
        add_to_profile.setAttribute("class", "add_to_profile");
        add_to_profile.innerHTML = icon;
        add_to_profile_block.append(add_to_profile);
        progress_bar_value(-step);
    }
}
function progress_bar_value(step) {
    profile_filled_progress_bar.value += step;
    if(profile_filled_progress_bar.value <= 30)
        progress_bar_css.backgroundColor = "crimson";
    if(profile_filled_progress_bar.value > 30 && profile_filled_progress_bar.value < 70)
        progress_bar_css.backgroundColor = "rgb(255, 131, 21)";
    if(profile_filled_progress_bar.value > 70 && profile_filled_progress_bar.value < 100)
        progress_bar_css.backgroundColor = "rgb(7, 255, 46)";
    if(profile_filled_progress_bar.value == 100)
        progress_bar_css.backgroundColor = "rgb(255, 7, 251)";
}

function change_info() {
    info = document.getElementById("info");
    let info_change_accept = document.createElement("span");
    info_change_accept.setAttribute("id", "info_change_accept");
    info_change_accept.innerHTML = "<i class=\"fas fa-check\"></i>";
    let info_input = document.createElement("textarea");
    if(info.innerText.match("I'm Very Shy"))
        info_input.value = "";
    else
        info_input.value = info.innerText;
    info.innerText = "";
    info.onclick = null;
    info.append(info_input);
    info.append(info_change_accept);
    info_input.focus();
    info_input.addEventListener("keydown", function (e) {
        if(e.keyCode == 13)
            info_change_accept.click();
    });
    info_change_accept.onclick = function(){
        profile_save_settings("1", info_input.value.trim()); //Type 1 - Save new Info
        let info_change = document.createElement("span");
        info_change.setAttribute("id", "info_change");
        info_change.setAttribute("onclick", "change_info()");
        info_change.innerHTML = "<i class=\"fas fa-pencil-alt\"></i>";
        if(info_input.value.trim().length == 0) {
            info.innerHTML = "<i class=\"fas fa-user-ninja\"></i> I'm Very Shy";
            add_to_profile("add_info", "Info", "<i class='fas fa-info-circle'></i>", 20);
        }
        else {
            info.innerText = info_input.value.trim();
            if(document.getElementById("add_info")) {
                progress_bar_value(20);
                document.getElementById("add_info").remove();
            }
        }
        info.append(info_change);
        info_input.remove();
        info_change_accept.remove();
    };
}
function change_geo() {
    city_selected = false;
    geo = document.getElementById("geo");
    let last_city = geo.innerText;
    geo.innerHTML = '<i class="fas fa-location-arrow" aria-hidden="true"></i>' +
        '<input id="address" name="address" type="text">';
    load_city_input(current_token);
    let geo_change_accept = document.createElement("span");
    let geo_change_input = document.getElementById("address");
    geo_change_input.focus();
    geo_change_accept.setAttribute("id", "info_change_accept");
    geo_change_accept.innerHTML = "<i class=\"fas fa-check\"></i>";
    geo.append(geo_change_accept);
    geo_change_input.addEventListener("keydown", function (e) {
        if(e.keyCode == 13)
            geo_change_accept.click();
    });
    geo_change_accept.onclick = function () {
        profile_save_settings("2", geo_change_input.value); //Type 2 - Save new Geo
        let geo_change = document.createElement("span");
        geo_change.setAttribute("id", "geo_change");
        geo_change.setAttribute("onclick", "change_geo()");
        geo_change.innerHTML = "<i class=\"fas fa-pencil-alt\"></i>";
        if(city_selected)
            geo.innerHTML = "<i class=\"fas fa-location-arrow\"></i> " + geo_change_input.value;
        else
            geo.innerHTML = "<i class=\"fas fa-location-arrow\"></i> " + last_city;
        geo.append(geo_change);
        geo_change_accept.remove();
    };
}
function load_city_input(token) {
    $("#address").suggestions({
        token: token,
        type: "ADDRESS",
        bounds: "city",
        constraints: {
            label: "",
            locations: { city_type: "город" }
        },
        onSelect: function(suggestion) {
           document.getElementById("address").value = suggestion['data']['city'];
           city_selected = true;
        }
    });
}
function load_token(token) {
    current_token = token;
}
function profile_save_settings(setting_type, setting_value) {
    $.ajax({
        url: "/profile/save_settings",
        method: "POST",
        data: {"settings": {setting_type, setting_value}}
    })
}
function load_tags_button(){
    fill_tags();
    document.getElementById("add_new_tag").remove();
}

function remove_tag() {
   let tags = document.getElementsByClassName("tags");
   for(let i = 0; i < tags.length; i++){
       if(!tags[i]['checked']) {
           tags[i]['labels'][0]['attributes']['for']['ownerElement'].remove();
           profile_save_settings(8, tags[i].id); //Type 8 - Delete Tag
           document.getElementById(tags[i].id).remove();
       }
   }
   if(!tags.length && !document.getElementById("add_tags"))
       add_to_profile("add_tags", "Tags", "<i class='fas fa-hashtag'></i>", 20);
}

function fill_tags() {
    tag_delete = false;
    $.ajax(
        {url: "/profile/load_tags",
            method: "POST",
            success: function (data) {
                for(let i = 0; i < data.length; i++){
                    let tag = document.createElement("input");
                    tag.setAttribute("type", "checkbox");
                    tag.setAttribute("name", "tags[]");
                    tag.setAttribute("class", "tags");
                    tag.setAttribute("id", data[i]['tag_name']);
                    tag.setAttribute("value", data[i]['tag_name']);
                    let tag_label = document.createElement("label");
                    tag_label.setAttribute("for", data[i]['tag_name']);
                    tag_label.setAttribute("class", "tags_labels");
                    tag_label.setAttribute("style", "color: " + data[i]['tag_color']);
                    tag_label.innerHTML = data[i]['tag_icon'] + " " + data[i]['tag_name'];
                    tags.append(tag);
                    tags.append(tag_label);
                }
                let tags_change_accept = document.createElement("div");
                tags_change_accept.innerHTML = "<i class=\"fas fa-check\"></i>";
                tags_change_accept.setAttribute("id", "tags_change_accept");
                tags.append(tags_change_accept);
                tags_change_accept.onclick = function () {
                    let tags_list = tags.getElementsByClassName("tags");
                    let tags_to_delete = new Array();
                    let tags_to_save = new Array();
                    for(let i = 0; i < tags_list.length; i++){
                        if(!tags_list[i]['checked']) {
                            tags_to_delete.push(tags_list[i].id);
                            tags_list[i]['labels'][0]['attributes']['for']['ownerElement'].remove();
                        }
                        else
                            tags_to_save.push(tags_list[i].id);
                    }
                    profile_save_settings(7, tags_to_save); //Type 7 - Save Tag
                    if(tags_to_save) {
                        if(document.getElementById("add_tags")) {
                            document.getElementById("add_tags").remove();
                            progress_bar_value(20);
                        }
                    }
                    for(let i = 0; i < tags_to_delete.length; i++)
                        document.getElementById(tags_to_delete[i]).remove();
                    this.remove();
                    let add_new_tag = document.createElement("div");
                    add_new_tag.setAttribute("id", "add_new_tag");
                    add_new_tag.setAttribute("onclick", "load_tags_button()");
                    add_new_tag.innerHTML = "<i class=\"fas fa-plus-circle\"></i>";
                    tags.append(add_new_tag);
                    tag_delete = true;
                    tags.onclick = function () {
                        if(tag_delete)
                            remove_tag();
                    };
                }
            }})
}