let current_like_status = 0;
let current_photo = 0;
let count_photo;
let main_photo;
let user_photos = [];
let photo;
let info;
let geo;
let main_photo_change_click = false;
let like_block = document.getElementById("like_block");
let main_photo_icon = document.getElementById("main_photo_icon");
let right_arrow = document.getElementById("right_arrow");
let left_arrow = document.getElementById("left_arrow");
let tags = document.getElementById("tags_block");
let current_token;
let main_photo_index = 0;



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
        like_block.innerHTML = "<i class=\"far fa-heart\"></i>";
        current_like_status = 0;
    }
    else {
        like_block.innerHTML = "<i class=\"fas fa-heart\"></i>";
        current_like_status = 1;
    }
    $.ajax({
        url: '/profile/like',
        method: 'POST',
        data: {"login": params['login']},
        success: function () {
            // alert("ok");
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
        console.log(user_photos);
    }
}

function remove_photo() {
    main_photo = 0;
    if(count_photo > 1) {
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
            count_photo += 1;
            if(count_photo >= 5)
                document.getElementById("add_photo").style.display = "none";
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
                user_photos.push({"photo_token": photo_token, "photo_src": image_data, "main_photo": main_photo});
                if(main_photo) {
                    profile_save_settings(6, {"photo_token": photo_token, "photo_base64": image_data}); //Type 6 - Add new Photo
                    // and Set Main Photo
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

function chat () {
    $.ajax({
        url: '/profile/chat',
        method: 'POST',
        data: {"login": params['login']},
        success: function (data) {
            // alert(data)
        }
    });
}
function change_info() {
    info = document.getElementById("info");
    let info_change_accept = document.createElement("span");
    info_change_accept.setAttribute("id", "info_change_accept");
    info_change_accept.innerHTML = "<i class=\"fas fa-check\"></i>";
    let info_input = document.createElement("textarea");
    info_input.value = info.innerText;
    info.innerText = "";
    info.onclick = null;
    info.append(info_input);
    info.append(info_change_accept);
    info_input.focus();
    info_change_accept.onclick = function(){
        profile_save_settings("1", info_input.value); //Type 1 - Save new Info
        let info_change = document.createElement("span");
        info_change.setAttribute("id", "info_change");
        info_change.setAttribute("onclick", "change_info()");
        info_change.innerHTML = "<i class=\"fas fa-pencil-alt\"></i>";
        info.innerText = info_input.value;
        info.append(info_change);
        info_input.remove();
        info_change_accept.remove();
    };
}
function change_geo() {
    geo = document.getElementById("geo");
    geo.innerHTML = '<i class="fas fa-location-arrow" aria-hidden="true"></i>' +
        '<input id="address" name="address" type="text">';
    load_city_input(current_token);
    let geo_change_accept = document.createElement("span");
    let geo_change_input = document.getElementById("address");
    geo_change_accept.setAttribute("id", "info_change_accept");
    geo_change_accept.innerHTML = "<i class=\"fas fa-check\"></i>";
    geo.append(geo_change_accept);
    geo_change_accept.onclick = function () {
        profile_save_settings("2", geo_change_input.value); //Type 2 - Save new Geo
        let geo_change = document.createElement("span");
        geo_change.setAttribute("id", "geo_change");
        geo_change.setAttribute("onclick", "change_geo()");
        geo_change.innerHTML = "<i class=\"fas fa-pencil-alt\"></i>";
        geo.innerHTML = "<i class=\"fas fa-location-arrow\"></i> " + geo_change_input.value;
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
        }
    });
}
function load_token(token) {
    current_token = token;
}
function profile_save_settings(setting_type, setting_value) {
    $.ajax({
        url: "profile/save_settings",
        method: "POST",
        data: {"settings": {setting_type, setting_value}}
    })
}
function load_tags_button(){
    fill_tags();
    document.getElementById("add_new_tag").remove();
}
function fill_tags() {
    $.ajax(
        {url: "/first_login/load_tags",
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
                    for(let i = 0; i < tags_list.length; i++){
                        if(!tags_list[i]['checked']) {
                            tags_to_delete.push(tags_list[i].id);
                            tags_list[i]['labels'][0]['attributes']['for']['ownerElement'].remove();
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
                }
            }})
}