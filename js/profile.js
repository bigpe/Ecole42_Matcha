var params = window
    .location
    .search
    .replace('?', '')
    .split('&')
    .reduce(
        function (p, e) {
            var a = e.split('=');
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
