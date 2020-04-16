let width;

onload = function () {
    width = document.body.clientWidth;
    if (typeof window.orientation !== 'undefined' || width <= 550)
        resize_header();
};

function find_pointer_for_style(css_rule){
    let styleSheetList = document.styleSheets;
    //Find Current Controller Name, Anchor Remove
    history.pushState(null, null, window.location.href.split('#')[0]);
    let controller_name = document.documentURI.split("/")[3].toLowerCase();
    let css_name;
    let css_rules;
    for (let i = 0; i < styleSheetList.length; i++){
        //Find All StyleSheets for this Document
        css_name = styleSheetList[i].href.split("/").splice(-1)[0];
        //Controller Name Must be same name a .css file
        if(css_name.match(controller_name + ".css")) {
            css_rules = styleSheetList[i].cssRules;
            for (let j = 0; j < css_rules.length; j++){
                if(css_rules[j].selectorText && css_rules[j].selectorText.match(css_rule)){
                    //Get and Return Css Rule
                    return (css_rules[j].style);
                    break;
                }
            }
            break;
        }
    }
}

function progress_bar_value(step) {
    profile_filled_progress_bar.value += step;
    if(profile_filled_progress_bar.value <= 30)
        progress_bar_css.backgroundColor = "crimson";
    if(profile_filled_progress_bar.value > 30 && profile_filled_progress_bar.value < 70)
        progress_bar_css.backgroundColor = "rgb(255, 131, 21)";
    if(profile_filled_progress_bar.value > 70 && profile_filled_progress_bar.value < 100)
        progress_bar_css.backgroundColor = "rgb(7, 255, 46)";
    if(profile_filled_progress_bar.value == 100)
        progress_bar_css.backgroundColor = "rgb(255, 7, 251)";
}

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