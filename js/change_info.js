window.onload = function()
{
    let dPhoto = document.getElementById('d_photo');
    let dfname =  document.getElementById('dfname');
    let dlname = document.getElementById('dlname');
    let dsex=  document.getElementById('dsex');
    let dsex_pref =  document.getElementById('dsex_pref');
    let dinfo =  document.getElementById('dinfo');
    let dtags =  document.getElementById('dtags');
    $.ajax({
        url: '/profile/get_data',
        method: 'POST',
        success: function (data) {
            let info = JSON.parse(data);
            console.log(info);
            let photo = document.createElement('img');
            photo.setAttribute('src', info.user_pic);
            dPhoto.appendChild(photo);
            let f_name = document.createElement("h3");
            f_name.setAttribute("style", "color:#ff0000")
            f_name.innerText = info.f_name;
            dfname.append(f_name);
            let lname = document.createElement("h3");
            lname.setAttribute("style", "color:#ff0000")
            lname.innerText = info.l_name;
            dlname.appendChild(lname);
            let sex = document.createElement("h3");
            sex.setAttribute("style", "color:#ff0000")
            sex.innerText = info.sex;
            dsex.appendChild(sex);
            let sex_preference = document.createElement("h3");
            sex_preference.setAttribute("style", "color:#ff0000")
            sex_preference.innerText = info.preference;
            dsex_pref.appendChild(sex_preference);
            let user_info = document.createElement("h3");
            user_info.setAttribute("style", "color:#ff0000")
            user_info.innerText = info[7];
            dinfo.appendChild(user_info);
            for(var index in info.tags)
            {
                let tag = document.createElement("h3");
            tag.setAttribute("style", "color:#ff0000")
                tag.innerText = "#"+ info.tags[index]['name_tag'] + " ";
                dtags.appendChild(tag);
            }
        }
    })
}

document.getElementById("upload_button").onclick = function () {
    let  userPic = document.getElementById('user_pic');
    let userPicFile = userPic.files[0];
    let file_reader = new FileReader();
    file_reader.readAsDataURL(userPicFile);
    file_reader.onload = function () {
        let image_data = file_reader.result;
        $.ajax({
            url: '/profile/add_photo',
            method: 'POST',
            data: {"user_pic" : image_data},
            success: function (data) {
                alert( "Прибыли данные: " + data );
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

