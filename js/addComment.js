function addComment() {
    let get = window.location.search
        .replace('?', '')
        .split('&')
        .reduce(
            function (p, e) {
                let a = e.split('=');
                p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            }, {}
        );

    let photo_id = get['id'];
    let form = document.getElementById('comment_input');
    let comment = form.value;
    form.value = "";
    pushComment(photo_id, comment);



    function pushComment(photo_id, comment) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/photo/add_comment", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4)
                if (xhr.status === 200)
                    uploadComment(comment);
        };
        xhr.send("photo_id=" + photo_id + "&comment=" + comment);
    }

    function uploadComment(comment) {
        let commentsBlock = document.getElementById('comments');
        let commentDiv = document.createElement('div');
        commentDiv.setAttribute('class', 'comment');
        commentDiv.innerHTML = "<h1>You</h1><br><p>" + comment + "</p>";
        document.body.insertBefore(commentDiv, commentsBlock);
    }
}