<script>
    var token = "470bf8c1890ac6915e8ed7b05ea27121a0c324c0";
    function iplocate(ip) {
        var serviceUrl = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address";
        if (ip) {
            serviceUrl += "?ip=" + ip;
        }
        var params = {
            type: "GET",
            contentType: "application/json",
            headers: {
                "Authorization": "Token " + token
            }
        };
        return $.ajax(serviceUrl, params);
    }
    function detect() {
        iplocate().done(function(response) {
            console.log(response['location']['value']);
        })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown);
            });
    }
    detect();
</script>
