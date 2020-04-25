let tags = document.getElementById("tags");
let user_photo_block = document.getElementById("user_photo_block");
let user_photo_input = document.getElementById("user_photo_input");
let user_photo_button = document.getElementById("user_photo_button");
let load_tags_button = document.getElementById("load_tags_button");
let mandatory_form = document.getElementById("mandatory_form");
let mandatory_form_submit = document.getElementById("mandatory_form_submit");
let user_photo_tips = document.getElementById("user_photo_tips");
let sex_block = document.getElementById("sex_block");
let sex_preference_block = document.getElementById("sex_preference_block");
let geo = document.getElementById("address");
let geo_longitude = document.getElementById("geo_longitude");
let geo_latitude = document.getElementById("geo_latitude");
let user_photo_count = 0;
let timeout;
let lastTap = 0;
let animation_duration = parseFloat(find_pointer_for_style(".highlight").animationDuration) * 1000;
let ajax_complete = false;
let geo_detect_button = document.getElementById('geo_detect_button');


function set_highlight(node) {
    node.setAttribute("class", "highlight");
    //Remove animation Node Name after Complete Animation
    setTimeout(remove_highlight, animation_duration, node);
    //Anchor provide to this node id name + _pin
    location.hash = "#" + node.id.toString() + "_pin";
}
function remove_highlight (node){
    node.removeAttribute("class");
}

mandatory_form.onsubmit = function() {
    return(check_form());
};

function check_form() {
    //Check Mandatory form element, don't submit if any return False
    if(check_at_least_one_checked(tags) && check_at_least_one_checked(sex_block)
        && check_at_least_one_photo(user_photo_block, "user_photo") && check_at_least_one_checked(sex_preference_block)
        && check_at_least_one_checked(user_photo_block) && !ajax_complete) {
        let result = new Array();
        let images = user_photo_block.getElementsByClassName("user_photo");
        //Collect all uploaded picture for send to Ajax request
        for (let i = 0; i < images.length; i++){
            result.push({"image_base64": images[i].src, "image_token": images[i].id});
        }
        $.ajax({url: "/first_login/save_photos",
            method: "POST",
            data: {"images": JSON.stringify(result)},
            beforeSend: function (){
                mandatory_form_submit.valueOf().value = "Loading...";
                mandatory_form_submit.setAttribute("disabled", "disabled");
            },
            complete: function () {
                ajax_complete = true;
                mandatory_form_submit.removeAttribute("disabled");
                mandatory_form_submit.valueOf().value = "Success!";
                mandatory_form_submit.click();
            }});
    }
    return(ajax_complete);
}

function check_at_least_one_photo(node, class_name) {
    if(node.getElementsByClassName(class_name).length)
        return (true);
    set_highlight(node);
    return (false);
}

function check_at_least_one_checked(node){
    let node_input = node.getElementsByTagName("input");
    for(let i = 0; i < node_input.length; i++) {
        if (node_input[i].checked)
            return (true);
    }
    set_highlight(node);
    return (false);
}

load_tags_button.onclick = function(){
    fill_tags();
    load_tags_button.remove();
};


user_photo_input.onchange = function () {
    load_photos();
};

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
            geo.value = suggestion['data']['city'];
            geo_latitude.value = suggestion['data']['geo_lat'];
            geo_longitude.value = suggestion['data']['geo_lon'];
        }
    });
}

function load_photos() {
    if(user_photo_input.files.length >= 5)
        user_photo_button.setAttribute("disabled", "disabled");
    for (let i = 0; i < user_photo_input.files.length; i++){
        let file_reader = new FileReader();
        file_reader.readAsDataURL(user_photo_input.files[i]);
        if(user_photo_count >= 5) {
            user_photo_count = 5;
            user_photo_button.setAttribute("disabled", "disabled");
            break;
        }
        user_photo_count++;
        file_reader.onload = function () {
            let photo_token = Date.now();
            let image = new Image();
            let photo_label = document.createElement("label");
            let photo_input = document.createElement("input");
            image.src = file_reader.result;
            image.setAttribute("class", "user_photo");
            image.setAttribute("id", (photo_token).toString());
            photo_label.append(photo_input);
            photo_label.append(image);
            photo_input.setAttribute("type", "radio");
            photo_input.setAttribute("name", "user_main_photo");
            photo_input.setAttribute("value", (photo_token).toString());
            photo_input.setAttribute("class", "user_main_photo");
            if(user_photo_count == 1)
                photo_input.setAttribute("checked", "checked");
            user_photo_block.append(photo_label);
            image.onclick = function () {
                if(event.detail == 2) {
                    remove_photo();
                }
            };
            image.addEventListener('touchend', function(event) {
                let currentTime = new Date().getTime();
                let tapLength = currentTime - lastTap;
                clearTimeout(timeout);
                if (tapLength < 200 && tapLength > 0) {
                    remove_photo();
                    event.preventDefault();
                } else {
                    timeout = setTimeout(function() {
                        clearTimeout(timeout);
                    }, 200);
                }
                lastTap = currentTime;
            });
            function remove_photo() {
                user_photo_button.removeAttribute("disabled");
                photo_label.remove();
                user_photo_count--;
                if(!user_photo_count) {
                    user_photo_tips.style.display = "none";
                    user_photo_tips.removeAttribute("class");
                }
            }
        };
    }
    location.hash = "#user_photo_button_block";
    user_photo_tips.style.display = "inherit";
    user_photo_tips.setAttribute("class", "highlight");
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
            }})
}
function get_location(token) {
    let serviceUrl = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=";
    $.ajax({
        url: serviceUrl,
        method: "GET",
        contentType: "application/json",
        headers: {"Authorization": "Token " + token},
        success: function (data) {
            geo.value = data['location']['data']['city'];
            geo_latitude.value = data['location']['data']['geo_lat'];
            geo_longitude.value = data['location']['data']['geo_lon'];
        }})
}
user_photo_button.onclick = function () {
    user_photo_input.click();
};

if (!deviceDetect()){
    geo_detect_button.style.display= 'none';
}
geo_detect_button.onclick = function(){
    let startPos;
    let geoOptions = {
        enableHighAccuracy: true
    };
    let geoSuccess = function(position) {
        startPos = position;
        geo_latitude.value = startPos.coords.latitude;
        geo_longitude.value = startPos.coords.longitude;
    };
    let geoError = function(error) {
        console.log('Error occurred. Error code: ' + error.code);
    };
    navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
};
