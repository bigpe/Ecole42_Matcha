let width;

onload = function () {
    width = document.body.clientWidth;
    if (typeof window.orientation !== 'undefined' || width <= 550)
        resize_header()
};
onresize = function () {
    width = document.body.clientWidth;
    if(width <= 550)
        resize_header();
    if(width > 550)
        restore_header()
};
function resize_header() {
    let logo = document.getElementById("logo");
    let header = document.getElementsByTagName("header")[0];
    let header_buttons = document.getElementById("header_buttons");
    logo.style.display = "block";
    header_buttons.style.fontSize = ".8em";
    header.prepend(logo);
}
function restore_header() {
    let logo = document.getElementById("logo");
    let header_buttons = document.getElementById("header_buttons");
    logo.style.display = "table-cell";
    header_buttons.style.fontSize = "1em";
    header_buttons.prepend(logo);
}