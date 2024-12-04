window.addEventListener("load",function (){
    addEvent();
});

function addEvent(){
    let formAdd

    let formData = new FormData()
    formData.append("admin","getFormulaire")
    fetch('ajax/ajaxAdmin.php',{
        method : "POST",
        body : formData
    }).then(res => {
        if (res.status !== 200){
            throw new Error("Bad server response");
        }
        return (res.text());
    }).then(res=> {
        formAdd = res;
    }).catch(err => {
        console.log(err);
    })

    let btnSend = document.getElementById("sendAdmin");
    if (btnSend !== null){
        btnSend.addEventListener("click",function (){
            sendForm();
        },false);
    }

    $('#dataTable').on('click','.modify', function(){
        modifForm(this,formAdd);
    });

    $('#addButton').on('click',function(){
        getModal(formAdd);
    });
}

function sendForm() {
    let form = document.getElementById("formAdmin");
    if(form !== null){
        let formData = new FormData(form);
        formData.append("admin","addForm");


        fetch("ajax/ajaxAdmin.php",{
            method: "POST",
            body: formData
        }).then(res=>{
            if(res.status !== 200){
                throw new  Error("Bad server response");
            }
            return (res.text());
        }).then(res=> {
            let div = document.getElementsByClassName('modal-body')[0];
            let divRes = document.createElement('div');
            if (!document.getElementById('returnMessage')) {
                divRes.setAttribute('id', 'returnMessage');
            } else {
                document.getElementById('returnMessage').remove();
                divRes.setAttribute("id", "returnMessage");
            }
            divRes.innerHTML = res;
            div.appendChild(divRes);
            let success = document.getElementsByClassName('alert-success');
            if (success.length === 1) {
                let buttonClose = document.getElementById('closeAdmin');
                if (buttonClose !== null) {
                    buttonClose.addEventListener('click', function () {
                        location.reload();
                    }, false);
                }
            }
            if (id === null) {
                let form = document.getElementById("formAdmin");
                if (form !== null) {
                    form.reset();
                }
            }
        }).catch(err=> {
            console.log(err);
        });
    }
}

function modifForm(button){
    let tdElement = $(button).closest('tr').find('td');
    let id = tdElement[0].innerText;
    let modal = document.getElementsByClassName('modal-lg')[0];
    if(modal !== undefined){
        modal.classList.replace("modal-lg","modal-xl");
    }
    document.getElementsByClassName("modal-title")[0].innerHTML = "Modifier un utilisateur";

    let formData = new FormData();
    formData.append("admin","getFormModif");
    formData.append("id",id);

    let btnSend = document.getElementById("sendAdmin");
    if (btnSend !== null){
        btnSend.addEventListener("click",function (){
            sendForm();
        },false);
    }

    fetch('ajax/ajaxAdmin.php',{
        method : "POST",
        body : formData
    }).then(res => {
        if (res.status !== 200){
            throw new Error("Bad server response");
        }
        return (res.text());
    }).then(res=> {
        let form = document.getElementsByClassName('modal-body')[0];
        form.innerHTML = res;

        let btnSend = document.getElementById("sendAdmin");
        if (btnSend !== null){
            btnSend.addEventListener("click",function (){
                let form = document.getElementById('formAdmin');
                if (form !== null) {
                    let formData = new FormData(form);
                    formData.append('admin', 'addModifyForm')
                    formData.append('id', id)

                    fetch('ajax/ajaxAdmin.php',{
                        method : "POST",
                        body : formData
                    }).then(res => {
                        if (res.status !== 200) {
                            throw new Error("Bad server response");
                        }
                        return (res.text());
                    }).then(res =>{
                        let div = document.getElementsByClassName('modal-body')[0];
                        let divRes = document.createElement('div');
                        if (!document.getElementById('returnMessage')) {
                            divRes.setAttribute('id', 'returnMessage');
                        } else {
                            document.getElementById('returnMessage').remove();
                            divRes.setAttribute("id", "returnMessage");
                        }
                        divRes.innerHTML = res;
                        div.appendChild(divRes);
                        let success = document.getElementsByClassName('alert-success');
                        if (success.length === 1) {
                            let buttonClose = document.getElementById('closeAdmin');
                            if (buttonClose !== null) {
                                buttonClose.addEventListener('click', function () {
                                    location.reload();
                                }, false);
                            }
                        }
                    })

                }
            },false);
        }

    }).catch(err => {
        console.log(err);
    });
}

function getModal(formHTML){
    let modalBody = document.getElementsByClassName('modal-body')[0];
    let title = document.getElementsByClassName("modal-title")[0];
    if(modalBody !== null && title !== null){
        modalBody.innerHTML = formHTML;
        title.innerHTML = "Ajouter un utilisateur";

        let btnSend = document.getElementById("sendAdmin");
        if (btnSend !== null){
            btnSend.addEventListener("click",function (){
                sendForm();
            },false);
        }
    }
}