function burger_menu_open() {
    setStyle('0', 'block');
}


function burger_menu_close() {
    setStyle('-180px', 'none');
}


function setStyle(rightValue, displayValue) {
    document.getElementById('nav_bar').style['right'] = rightValue;
    document.getElementById('nav_bar_overlay').style['display'] = displayValue;
}
