function unblock_user() {
    let node = this;
    this.parentNode.setAttribute("id", "remove_user");
    setTimeout(function () {
        node.parentNode.remove();
        if(!document.getElementsByClassName("people").length){
            document.getElementById("people_block").innerHTML = "<div id='system_message'><h2>Not Blocked Users</h2></div>";
        }
    }, 500);
    $.ajax({
        url: "/settings/black_list_remove",
        method: "POST",
        data: {"login": this.previousElementSibling.innerText}
    });
}