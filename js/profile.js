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
    $.ajax({
        url: '/profile/like',
        method: 'POST',
        data: {"login": params['login']},
        success: function () {
            // alert("ok");
        }
    });
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

window.onload = function() {
    var startPos;
    var geoOptions = {
        enableHighAccuracy: true
    };

    var geoSuccess = function(position) {
        startPos = position;
        console.log(startPos.coords.latitude);
        console.log(startPos.coords.longitude);
        geo = startPos;
    };

    var geoError = function(error) {
        console.log('Error occurred. Error code: ' + error.code);

        //   0: unknown error
        //   1: permission denied
        //   2: position unavailable (error response from location provider)
        //   3: timed out
    };
    navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
};