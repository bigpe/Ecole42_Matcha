let tags = document.getElementById("tags");
let user_photo = document.getElementById("user_photo");
let user_photo_input = document.getElementById("user_photo_input");
let user_photo_button = document.getElementById("user_photo_button");
let user_photo_count = 0;

onload = function () {
    fill_tags();
};
user_photo_input.onchange = function () {
    if(user_photo_input.files.length == 5) {
        user_photo_count = 5;
        user_photo_button.setAttribute("disabled", "disabled");
    }
    if(user_photo_input.files.length > 5){
        user_photo_input.value = null;
        alert("Too many photos to upload");
    }
    else {
        user_photo_count += user_photo_input.files.length;
        for (let i = 0; i < user_photo_input.files.length; i++){
            let file_reader = new FileReader();
            file_reader.readAsDataURL(user_photo_input.files[i]);
            file_reader.onload = function () {
                let image = new Image(50);
                image.src = file_reader.result;
                user_photo.append(image);
                image.onclick = function () {
                    user_photo_button.removeAttribute("disabled");
                    user_photo_count -= 1;
                    image.remove();
                }
            };
            if(user_photo_count >= 5) {
                user_photo_count = 5;
                user_photo_button.setAttribute("disabled", "disabled");
                break;
            }
        }
    }
};
user_photo_button.onclick = function () {
    user_photo_input.click();
};
function fill_tags() {
    $.ajax(
        {url: "/first_login/get_tags",
            method: "POST",
            success: function (data) {
                for(let i = 0; i < data.length; i++){
                    let tag = document.createElement("input");
                    tag.setAttribute("type", "checkbox");
                    tag.setAttribute("name", "tags[]");
                    tag.setAttribute("class", "tags");
                    tag.setAttribute("id", data[i]['tag_name']);
                    tag.setAttribute("value", (i+1).toString());
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