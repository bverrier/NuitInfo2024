window.addEventListener("load",function (){
    addEvent();
});
function deleteEvent(button, formDelete){

    let formData = new FormData()
    formData.append("operationType","getDeleteModal")
    fetch('ajax/ajaxHolidays.php',{
        method : "POST",
        body : formData
    }).then(res => {
        if (res.status !== 200){
            throw new Error("Bad server response");
        }
        return (res.text());
    }).then(res=> {
        formDelete = res;
    }).catch(err => {
        console.log(err);
    })
    deleteForm(button,formDelete);
}
function addEvent(){
    let formAdd
    let formDelete;
    let formData = new FormData()
    formData.append("holidays","getFormulaire")
    fetch('ajax/ajaxHolidays.php',{
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

    let btnSend = document.getElementById("sendHolidays");
    if (btnSend !== null){
        btnSend.addEventListener("click",function (){
            sendForm("addHoliday");
        },false);
    }

    $('#MyDataTable').on('click','.modify', function(){
        modifForm(this,formAdd);
    });
    $('#MyDataTable').on('click','.delete', function(){
        deleteEvent(this, formDelete);
    });
    /* $('#addButton').on('click',function(){
         getModal(formAdd);
    });*/
}
function modifForm(button){
    let tdElement = $(button).closest('tr').find('td');
    let id = tdElement[0].innerText;
    let modal = document.getElementsByClassName('modal-lg')[0];
    if(modal !== undefined){
        modal.classList.replace("modal-lg","modal-xl");
    }
    document.getElementsByClassName("modal-title")[0].innerHTML = "Modifier une période de vacance";

    let formData = new FormData();
    formData.append("operationType", "getFormModif");
    formData.append("id",id);

    let btnSend = document.getElementById("sendHolidays");
    if (btnSend !== null){
        btnSend.addEventListener("click",function (){
            sendForm();
        },false);
    }

    fetch('ajax/ajaxHolidays.php',{
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

        let btnSend = document.getElementById("sendHolidays");
        if (btnSend !== null){
            btnSend.addEventListener("click",function (){
                let form = document.getElementById('formHoliday');
                if (form !== null) {
                    let formData = new FormData(form);
                    formData.append("operationType", "ModifHoliday");
                    formData.append('id', id)

                    fetch('ajax/ajaxHolidays.php',{
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
                            let buttonClose = document.getElementById('closeVacances');
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
function sendForm(operationType) {
    let form = document.getElementById("formHoliday");
    if(form !== null){
        let formData = new FormData(form);

        // Add the operation type (add, update, delete) to the FormData object
        formData.append("operationType", operationType);

        let holidays = [];
        let tempHoliday = {};

        formData.forEach((value, key) => {
            // Group form data by related fields (holidayName, StartDate, EndDate)
            if (key === "Nom") {
                if (Object.keys(tempHoliday).length > 0) {
                    holidays.push(tempHoliday); // push the previous holiday entry
                }
                tempHoliday = {}; // start a new holiday entry
                tempHoliday.Nom = value;
            } else if (key === "debut") {
                tempHoliday.debut = value;
            } else if (key === "fin") {
                tempHoliday.fin = value;
            }
        });

        // Push the last holiday entry
        if (Object.keys(tempHoliday).length > 0) {
            holidays.push(tempHoliday);
        }

        // Append the holidays array to FormData as a JSON string
        formData.append("holidays", JSON.stringify(holidays));

        // Send form data via fetch
        fetch("ajax/ajaxHolidays.php",{
            method: "POST",
            body: formData
        }).then(res => {
            if(res.status !== 200){
                throw new  Error("Bad server response");
            }
            return res.text();
        }).then(res => {
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
                let buttonClose = document.getElementById('closeVacances');
                if (buttonClose !== null) {
                    buttonClose.addEventListener('click', function () {
                        location.reload();
                    }, false);
                }
            }
        }).catch(err => {
            console.log(err);
        });
    }
}
function deleteForm(button){
    let tdElement = $(button).closest('tr').find('td');
    let id = tdElement[0].innerText;
    let formData = new FormData();
    formData.append("operationType", "getDeleteModal");
    let modal = document.getElementsByClassName('modal-lg')[0];
    if(modal !== undefined){
        modal.classList.replace("modal-lg","modal-xl");
    }
    document.getElementsByClassName("modal-title")[0].innerHTML = "Voulez vous Supprimer une période de vacance?";
    fetch('ajax/ajaxHolidays.php',{
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
        let btnYes = document.getElementById("deleteYes");
        let btnNo = document.getElementById("deleteNo");
        console.log(btnNo)
        if (btnYes !== null) {
            btnYes.addEventListener("click", function () {


                let formData = new FormData();
                formData.append("operationType", "deleteHoliday");
                formData.append('id', id);

                fetch('ajax/ajaxHolidays.php', {
                    method: "POST",
                    body: formData
                }).then(res => {
                    if (res.status !== 200) {
                        throw new Error("Bad server response");
                    }
                    return (res.text());
                }).then(res => {
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
                        let buttonClose = document.getElementById('closeVacances');
                        if (buttonClose !== null) {
                            buttonClose.addEventListener('click', function () {
                                location.reload();
                            }, false);
                        }
                    }
                })


            })
        }
        if (btnNo !== null) {
            btnNo.addEventListener("click", function (e) {

                    $('#Vacances').hide();
                    $(".modal-backdrop").removeClass("show").remove();
                    //$("body").removeClass("modal-open")
                }
            )

        }


    })}
$(document).on('click', '.js-add--holiday-row', function (e) {
    e.preventDefault();

    var holidaysList = $('#holidays-list');
    var clone = holidaysList.children('.form-group:first').clone(true);

    // Add a "Remove" button to the cloned row
    clone.find('.d-flex').append(
        $('<div class="col-sm-4 offset-1">').append(
            $('<button>')
                .addClass('btn btn-danger btn-sm js-remove--holiday-row')
                .html('<i class="fa-solid fa-minus"></i>')
        )
    );
    // Reset values in cloned inputs and add enumerated IDs
    clone.find('input').val('').attr('id', function () {
        return $(this).attr('id') + '_' + (holidaysList.children('.form-group').length + 1);
    });

    // Append the cloned row to the list
    holidaysList.append(clone);
});

// Remove rows when "Remove" button is clicked
$(document).on('click', '.js-remove--holiday-row', function (e) {
    e.preventDefault();
    $(this).parent().parent().parent().remove();
});