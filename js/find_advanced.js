let time = null;
let people_block = document.getElementById("people_block");
let geo = document.getElementById("address");

onload = function () {
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
            $.ajax({
                url: "/find_advanced/save_filters",
                method: "POST",
                data: {"geo_filter": {"geo": suggestion['data']['city']}},
                success: function (data) {
                    data = JSON.parse(data);
                    fil_users(data);
                }})
        }
    });
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
        name.innerHTML = '<i class="fas fa-circle" style="color:'+ data[i]['online_status']['status']+'" title="'+data[i]['online_status']['last_online']+'"> </i> ' + data[i]['login'];
        people.append(name);
        url.append(people);
        people_block.append(url);
    }
}