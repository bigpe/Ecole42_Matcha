let current_like_status = 0;
let current_photo = 0;
let count_photo;
let user_photos = [];
let photo;
let like_block = document.getElementById("like_block");

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
    photo = document.getElementById(user_photos[current_photo]['photo_token']);
    current_photo += 1;
    if(current_photo == count_photo)
        current_photo = 0;
    photo.id = user_photos[current_photo]['photo_token'];
    let photo_path = 'url("' + user_photos[current_photo]['photo_src'] + '")';
    photo.style.backgroundImage = photo_path.replace(".", "");
    console.log(user_photos[current_photo]);
    console.log(photo)
}
function photo_backward() {
    photo = document.getElementById(user_photos[current_photo]['photo_token']);
    current_photo -= 1;
    if(current_photo < 0)
        current_photo = count_photo - 1;
    photo.id = user_photos[current_photo]['photo_token'];
    let photo_path = 'url("' + user_photos[current_photo]['photo_src'] + '")';
    photo.style.backgroundImage = photo_path.replace(".", "");
    console.log(user_photos[current_photo]);
    console.log(photo)
}

function like_status(l) {
    current_like_status = l;
}
function load_user_photos(photo_array) {
    for(let i = 0; i < photo_array.length; i++) {
        if(document.getElementById(photo_array[i]["photo_token"]))
            current_photo = i;
        user_photos.push(photo_array[i]);
        console.log(current_photo);
    }
    count_photo = photo_array.length;
    console.log(user_photos);
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