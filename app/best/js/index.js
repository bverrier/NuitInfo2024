$(document).ready(function () {
    $('#dataTable').DataTable({
        "pageLength" : 10 //Taille des tableaux
    });
});

document.addEventListener("DOMContentLoaded", function (){
    let loader = document.getElementsByClassName("spinner")[0];
    if (loader !== undefined) {
        loader.style.display = "block";
        loader.style.opacity = "1";
        setTimeout(function (){
            loader.style.opacity = "0";
            setTimeout(function (){
                loader.style.display = "none";
            },300); //ms
        },300); //ms
    }
});

