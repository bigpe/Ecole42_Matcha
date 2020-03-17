let userPic = document.createElement('input');
let dPhoto = document.getElementById('d_photo');
let dfname = document.getElementById('dfname');
let dlname = document.getElementById('dlname');
let dsex = document.getElementById('dsex');
let dsex_pref = document.getElementById('dsex_pref');
let dinfo = document.getElementById('dinfo');
let dtags = document.getElementById('dtags');
let upload_button = document.getElementById("upload_button");
let images_block = document.getElementById("images_block");
let image_count = 0;
let image_limit = 5;

window.onload = function()
{
    fil_profile();
};


function fil_profile(){
    loading(images_block);
    loading(dfname);
    loading(dlname);
    loading(dsex);
    loading(dsex_pref);
    $.ajax({
        url: '/profile/get_data',
        method: 'POST',
        success: function (data) {
            let info = JSON.parse(data);
            console.log(info);
            if(info.user_pic){
                for (let i = 0; i < info.user_pic.length; i++){
                    let image = new Image();
                    image.src = info.user_pic[i]['photo_src'];
                    images_block.append(image);
                    image.onclick = function(){
                        if(confirm("Remove this photo?"))
                            image.remove();
                    }
                }
                loading_remove(images_block);
            }
            image_count = parseInt(info['image_count']);
            if(image_count == image_limit)
                upload_button.setAttribute("disabled", "disabled");
            if(info.f_name){
                let f_name = document.createElement("h3");
                f_name.setAttribute("style", "color:#ff0000");
                f_name.innerText = info.f_name;
                dfname.append(f_name);
                loading_remove(dfname);
            }
            let lname = document.createElement("h3");
            lname.setAttribute("style", "color:#ff0000");
            lname.innerText = info.l_name;
            dlname.append(lname);
            loading_remove(dlname);
            let sex = document.createElement("h3");
            sex.setAttribute("style", "color:#ff0000");
            sex.innerText = info.sex;
            dsex.append(sex);
            loading_remove(dsex);
            let sex_preference = document.createElement("h3");
            sex_preference.setAttribute("style", "color:#ff0000");
            sex_preference.innerText = info.preference;
            dsex_pref.append(sex_preference);
            loading_remove(dsex_pref);
            let user_info = document.createElement("h3");
            user_info.setAttribute("style", "color:#ff0000");
            user_info.innerText = info[7];
            dinfo.append(user_info);
            loading_remove(dinfo);
            for(let index in info.tags) {
                let tag = document.createElement("h3");
                tag.setAttribute("style", "color:#ff0000");
                tag.innerText = "#"+ info.tags[index]['tag_name'] + " ";
                dtags.append(tag);
                loading_remove(dtags);
            }
        }
    })
}

function loading(node){
    let text_node = document.createElement("p");
    text_node.setAttribute("class", "loading");
    text_node.innerHTML = "<i class=\"fas fa-hourglass-half\"></i>";
    node.append(text_node);
}
function loading_remove(node){
    let text_node = node.getElementsByClassName("loading")[0];
    text_node.remove();
}

upload_button.onclick = function () {
    if(image_count != image_limit) {
        userPic.type = 'file';
        userPic.setAttribute("accept", "image/jpeg");
        userPic.setAttribute("multiple", "multiple");
        userPic.setAttribute("onchange", "Refresh_image()");
        image_count += 1;
        if(image_count == image_limit)
            upload_button.setAttribute("disabled", "disabled");
        userPic.click();
    }
    else
        alert("Max photo reached!");
};

function Refresh_image() {
    let image = new Image();
    let userPicFile = userPic.files[0];
    let file_reader = new FileReader();
    file_reader.readAsDataURL(userPicFile);
    file_reader.onload = function () {
        let image_data = file_reader.result;
        image.src = image_data;
        userPic.src = null;
        images_block.append(image);
        $.ajax({
            url: '/profile/add_photo',
            method: 'POST',
            data: {"user_pic" : image_data},
            success: function (data) {
                userPic.src = null;
            }
        })
    }
};

function changeFname() {
    let fname = document.getElementById('f_name').value;
    $.ajax({
        url: '/profile/change_f_name',
        method: 'POST',
        data: {"f_name" : fname},
        success: function (data) {
            alert( "Прибыли данные: " + data );
        }
})
}
function changeLname() {
    let lname = document.getElementById('l_name').value;
    $.ajax({
        url: '/profile/change_l_name',
        method: 'POST',
        data: {"l_name" : lname},
        success: function (data) {
            alert( "Прибыли данные: " + data );
        }
    });
}

function changSex() {
    let sex = document.getElementById('sex').value;
    $.ajax({
        url: '/profile/change_sex',
        method: 'POST',
        data: {"sex" : sex},
        success: function (data) {
            alert( "Прибыли данные: " + data );
        }
    });
}
function changeSexPref() {
    let sexPref = document.getElementById('sex_pref').value;
    $.ajax({
        url: '/profile/change_sex_pref',
        method: 'POST',
        data: {"sex_pref" : sexPref},
        success: function (data) {
            alert( "Прибыли данные: " + data );
        }
    });
}
function changeInfo() {
    let info = document.getElementById('info').value;
    $.ajax({
        url: '/profile/change_info',
        method: 'POST',
        data: {"info" : info},
        success: function (data) {
            alert( "Прибыли данные: " + data );
        }
    });
}
function changeTags() {
    let tags = document.getElementById('tags').value;
    $.ajax({
        url: '/profile/change_tags',
        method: 'POST',
        data: {"tags" : tags},
        success: function (data) {
            alert( "Прибыли данные: " + data );
        }
    });
}

