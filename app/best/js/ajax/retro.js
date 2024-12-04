window.addEventListener("load",function (){
    loadRetroCSS();
    hideAddButton();
});

function loadRetroCSS() {
    /*let styles = document.getElementsByTagName('link');
    for (let i = 0; i < styles.length; i++) {
        if (styles[i].rel === 'stylesheet') {
            styles[i].parentNode.removeChild(styles[i]);
        }
    }*/

    let link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = 'css/retro.css';
    document.head.appendChild(link);
}

function hideAddButton() {
    let addButton = document.getElementById('addButton');
    if (addButton) {
        addButton.style.display = 'none';  // Cache le bouton
    }
    let entete = document.getElementsByTagName('h1')[0];
    if (entete) {
        entete.style.display = 'none';  // Cache le titre
    }
}

