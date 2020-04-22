let time = null;
let tags_button_pressed = false;
let people_block = document.getElementById("people_block");
let geo = document.getElementById("address");
let fame_rating = document.getElementsByClassName("fame_rating");
let sex_preference = document.getElementsByClassName("sex_preference");
let age_sort = document.getElementsByClassName("age_sort");
let tags_add = document.getElementById("tags_add");
let tags_input_button = document.getElementById("tags_input_button");
let tags_input = document.getElementById("tags_input");
let tags_suggestion_block = document.getElementById("tags_suggestion_block");
let tags_block = document.getElementById("tags_block");
let system_message = document.getElementById("system_message");
let tags = new Array();
let image_error = 0;

onload = function () {
    if(document.getElementsByClassName("tags_labels")){
        let tags_labels = document.getElementsByClassName("tags_labels");
        for(let i = 0; i < tags_labels.length; i++){
            tags.push(tags_labels[i]['id'])
        }
    }
}

function change_fame() {
    for (let i = 0; i < fame_rating.length; i++){
        if(fame_rating[i]['checked'])
            $.ajax({
                url: "/find_advanced/save_filters",
                method: "POST",
                data: {"fame_filter": {"fame_rating": fame_rating[i].value}},
                success: function (data) {
                    data = JSON.parse(data);
                    fill_users(data);
                }
            })
    }
}

function load_slider(slider_name, min_value, max_value, chosen_min_value, chosen_max_value, postfix) {
    $("#" + slider_name).ionRangeSlider({
        type: "double",
        min: min_value,
        max: max_value,
        max_postfix: postfix,
        from: chosen_min_value,
        to: chosen_max_value,
        grid: true,
        onFinish: function (data) {
            if (time != null)
                clearTimeout(time);
            time = setTimeout(refresh_filter, 1000);
            function refresh_filter() {
                $.ajax({
                    url: "/find_advanced/save_filters",
                    method: "POST",
                    data: {"age_filter":
                            {"age_from": data['from'],
                            "age_to": data['to']}},
                    success: function (data) {
                        data = JSON.parse(data);
                        fill_users(data);
                    }})
            }
        }
    })
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
            geo.value = suggestion['data']['city'];
            $.ajax({
                url: "/find_advanced/save_filters",
                method: "POST",
                data: {"geo_filter": {"geo": suggestion['data']['city']}},
                success: function (data) {
                    data = JSON.parse(data);
                    fill_users(data);
                }})
        }
    });
}

function fill_users(data) {
    people_block.innerHTML = "";
    if(data.length)
        system_message.style.display = "none";
    else
        system_message.style.display = "grid";
    for (let i=0; i < data.length; i++) {
        image_error = 0;
        let url = document.createElement("a");
        url.setAttribute("href", "/profile/view/?login=" + data[i]['login']);
        let people = document.createElement("div");
        people.setAttribute("class", "people");
        let image = new Image();
        image.src = data[i]['photo_src'];
        image.onerror = function(){
            image_error = 1;
            people.setAttribute("style", "background: url('./images/placeholder.png') " +
                "no-repeat center; background-size: cover;");
        };
        image.onload = function () {
            image.remove();
        };
        if(!image_error)
            people.setAttribute("style", "background: url(" + data[i]['photo_src'] + ") " +
                "no-repeat center; background-size: cover;");
        let name = document.createElement("span");
        name.setAttribute("class", "name");
        name.innerHTML = '<i class="fas fa-circle" style="color:'+ data[i]['online_status']['status']+'" ' +
            'title="' + data[i]['online_status']['last_online']+'"> </i> ' + data[i]['login'];
        people.append(name);
        let distance = document.createElement("span");
        distance.setAttribute("class", "distance");
        let distance_string;
        if(data[i]['distance'])
            distance_string = Math.round(data[i]['distance'] / 1000).toString() + "km";
        else
            distance_string = "Around you";
        distance.innerHTML = "<i class=\"fas fa-map-marker-alt\"></i>" + distance_string;
        people.append(distance);
        url.append(people);
        people_block.append(url);
    }
}
tags_input_button.onclick = function () {
    if(!tags_button_pressed) {
        tags_input_button.innerHTML = "<i class=\"fas fa-check-circle\"></i>";
        tags_input.removeAttribute("disabled");
        tags_input.style.width = "150px";
        tags_input.focus();
        tags_button_pressed = true;
    }
    else{
        tags_input_button.innerHTML = "<i class=\"fas fa-plus-circle\"></i>";
        tags_input.setAttribute("disabled", "disabled");
        tags_input.style.width = "35px";
        tags_input.value = "";
        tags_button_pressed = false;
        tags_suggestion_block.innerHTML = "";
    }
    addEventListener("keydown", function (k) {
        if(k.keyCode == 13)
            tags_input_button.click();
    })
};
tags_input.oninput = function () {
    if(tags_input.value) {
        $.ajax({
            url: "/find_advanced/find_tags",
            method: "POST",
            data: {"keyword": tags_input.value},
            complete: function (data) {
                let tags_answer = JSON.parse(data.responseText);
                if(!tags_answer.length)
                    tags_suggestion_block.innerHTML = "";
                for (let i = 0; i < tags_answer.length; i++) {
                    if(!document.getElementById(tags_answer[i]['tag_name'])) {
                        let tags_suggestion = document.createElement("span");
                        tags_suggestion.setAttribute("id", tags_answer[i]["tag_name"]);
                        tags_suggestion.innerHTML = tags_answer[i]['tag_icon'];
                        tags_suggestion.style.color = tags_answer[i]['tag_color'];
                        tags_suggestion.setAttribute("class", "show_suggestion_elem");
                        tags_suggestion_block.append(tags_suggestion);
                        tags_suggestion.onclick = function () {
                            tags_suggestion.setAttribute("class", "destroy_suggestion_elem");
                            let suggestion_to_append = document.createElement("label");
                            suggestion_to_append.setAttribute("class", "tags_labels");
                            suggestion_to_append.setAttribute("id", tags_answer[i]['tag_name']);
                            suggestion_to_append.style.color = tags_answer[i]['tag_color'];
                            suggestion_to_append.innerHTML = tags_answer[i]['tag_icon'];
                            tags.push(tags_answer[i]['tag_name']);
                            save_tag(tags);
                            tags_block.insertBefore(suggestion_to_append, tags_add);
                            suggestion_to_append.onclick = function(){
                                remove_tag.apply(this);
                            };
                            setTimeout(function () {
                                tags_suggestion.remove();
                            }, 500)
                        }
                    }
                }
            }
        })
    }
    else
        tags_suggestion_block.innerHTML = "";
};
function remove_tag() {
    this.remove();
    for(let i = 0; i < tags.length; i++){
        if(tags[i] == this.id)
            tags.splice(i, 1);
    }
    save_tag(tags);
}
function save_tag(data) {
    if(!data.length) {
        $.ajax({
            url: "/find_advanced/delete_filter",
            method: "POST",
            data: {"filter_to_delete": "tags"},
            success: function (data) {
                data = JSON.parse(data);
                fill_users(data);
            }
        });
    }
    else {
        $.ajax({
            url: "/find_advanced/save_filters",
            method: "POST",
            data: {
                "tags_filter":
                    {"tags": data}
            },
            success: function (data) {
                data = JSON.parse(data);
                fill_users(data);
            }
        });
    }
}
function change_sex_preference() {
    for (let i = 0; i < sex_preference.length; i++){
        if(sex_preference[i]['checked'])
            $.ajax({
                url: "/find_advanced/save_filters",
                method: "POST",
                data: {"sex_filter": {"sex_preference": sex_preference[i].value}},
                success: function (data) {
                    data = JSON.parse(data);
                    fill_users(data);
                }
            })
    }
}
function change_age_sort() {
    for (let i = 0; i < age_sort.length; i++){
        if(age_sort[i]['checked'])
            $.ajax({
                url: "/find_advanced/save_filters",
                method: "POST",
                data: {"age_filter_sort": {"age_sort": age_sort[i].value}},
                success: function (data) {
                    data = JSON.parse(data);
                    fill_users(data);
                }
            })
    }
}