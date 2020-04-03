function unblock_user() {
    let node = this;
    this.parentNode.setAttribute("id", "remove_user");
    setTimeout(function () {
        node.parentNode.remove();
        if(!document.getElementsByClassName("people").length){
            document.getElementById("people_block").innerHTML = "<h2>Not blocked Users</h2>";
        }
    }, 1000);
    $.ajax({
        url: "/settings/black_list_remove",
        method: "POST",
        data: {"login": this.previousElementSibling.innerText}
    });
}