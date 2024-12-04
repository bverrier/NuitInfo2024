window.addEventListener("load",function (){
   addEvent();
});

function addEvent(){
    //ajout un event sur le bouton btn-success
    let btnSend = document.getElementById("sendRetro");
    if (btnSend !== null){
        btnSend.addEventListener("click",function (){
            redirection();
        },false);
    }
}

function redirection(){
    console.log("test");
    //redirection vers la page retro.php
    window.location.replace("index.php?page=Accueil");
}


