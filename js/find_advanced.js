let time = null;
let people_block = document.getElementById("people_block");

onload = function () {
    get_city();
};

function load_slider(age_from, age_to) {
    $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 18,
        max: 50,
        max_postfix: "+",
        from: age_from,
        to: age_to,
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
                        fil_users(data);
                    }})
            }
        }
    })
}
function get_city() {
    let ip = "37.204.240.149";
    let token = "470bf8c1890ac6915e8ed7b05ea27121a0c324c0";
    $.ajax({
        url: "https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=" + ip,
        method: "GET",
        headers: {"Authorization": "Token " + token},
    success: function (data) {
            console.log(data);
    }})
}

function fil_users(data) {
    people_block.innerHTML = "";
    for (let i=0; i < data.length; i++) {
        let url = document.createElement("a");
        url.setAttribute("href", "/profile/view/?login=" + data[i]['login']);
        let people = document.createElement("div");
        people.setAttribute("class", "people");
        people.setAttribute("style", "background: url(" + data[i]['photo_src'] + ") no-repeat center; background-size: cover;");
        let name = document.createElement("span");
        name.setAttribute("class", "name");
        name.innerHTML = '<i class="fas fa-circle" style="color: #5fe15f" aria-hidden="true"></i>' + data[i]['login'];
        people.append(name);
        url.append(people);
        people_block.append(url);
    }
}