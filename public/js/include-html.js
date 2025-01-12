async function includeHTML() {
    let includeElements = document.querySelectorAll('[w3-include-html]');
    for (let i = 0; i < includeElements.length; i++) {
        const element = includeElements[i];
        file = element.getAttribute("w3-include-html"); // "includes/header.html"
        let resp = await fetch(file);
        if (resp.ok) {
            element.innerHTML = await resp.text();
        } else {
            element.innerHTML = 'Page not found';
        }
    }
    setCurrentSite();
}

// highlight the link of the current site
function setCurrentSite() {
    let navBar = document.getElementById('nav_bar');
    let links = navBar.children;
    for (let index = 0; index < links.length; index++) {
        const link = links[index];
        if (document.URL.startsWith(link.href)) {
            link.classList.add('nav_hightlight');
            link.href = '#';
            break;
        }
    }
}