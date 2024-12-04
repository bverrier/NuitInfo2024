let iAmPresent = false;
let myNbCroissant = 0;

document.addEventListener("DOMContentLoaded", function () {
    $('.showInfo').on('click', function () {
        let tdElement = $(this).closest('tr').find('td');
        let eventId = tdElement[0].innerText;
        iAmPresent = tdElement[1].innerText;
        myNbCroissant = tdElement[2].innerText;
        addEvent(eventId)
        updateModalEventDetails(eventId)
    })

    document.getElementById("closeAccueil").addEventListener("click", () => {
        location.reload()
    })

    document.getElementById("addButton").hidden = true
})

function addEvent(eventId) {
    let btnValidate = document.getElementById("sendValiderPresenceEvent");
    if (btnValidate !== null) {
        btnValidate.addEventListener("click", function () {
            sendForm(eventId)
        })
    }
}

function sendForm(eventId)  {
    let form = document.getElementById("formValiderPresenceEvent")
    if (form !== null) {
        let formData = new FormData(form)
        formData.append('accueil', 'formValiderPresenceEvent')
        formData.append('id_event', eventId)

        fetch('ajax/ajaxAccueil.php', {
            method: "POST",
            body: formData
        }).then(res => {
            if (res.status !== 200) {
                throw new Error("Bad server response")
            }
            return (res.text())
        }).then(res => {
            res = JSON.parse(res)

            if (res['status'] === 'success') {
                iAmPresent = res['is_present']
                myNbCroissant = res['nb_croissant']
            }
            displayMessages(res['message'])
            updateModalEventDetails(eventId)
        }).catch(err => {
            console.log(err)
        })
    }
}

function updateModalEventDetails(eventId) {
    let modalTitle = document.getElementById("AccueilLabel");
    let modalDate = document.getElementById("detailsEventDate");
    let modalResponsable = document.getElementById("detailsEventResponsable");
    let modalParticipants = document.getElementById("detailsEventParticipants").getElementsByTagName('tbody')[0]
    let modalMessageEvent = document.getElementById("messageEvent");
    let modalContent = document.getElementById("modalContent");

    let form = document.getElementById("formValiderPresenceEvent");

    let formData = new FormData()
    formData.append("accueil", "detailsEvent")
    formData.append("detailsEventId", eventId)
    fetch('ajax/ajaxAccueil.php', {
        method: "POST",
        body: formData
    }).then(res => {
        if (res.status !== 200) {
            throw new Error("Bad server response");
        }
        return (res.json());
    }).then(res => {
        let responsable = res['participants'].filter((participant) => participant['is_responsable'] === '1')
        responsable = responsable.length > 0 ? responsable[0] : []

        modalTitle.textContent = ''
        modalDate.textContent = ''
        modalResponsable.textContent = ''
        modalParticipants.innerHTML = ''
        modalMessageEvent.textContent = ''
        modalContent.hidden = false

        modalTitle.textContent = "Croissant\'Show du " + res['date_event'];
        modalDate.textContent = res['date_event'];

        if (res['is_vacance'] === '1') {
            modalMessageEvent.textContent = 'L\'événement est annulé car c\'est les vacances!'
            modalContent.hidden = true;
            form.hidden = true;
        }
        else if (res['participants'].filter((participant) => participant['is_present'] === "1").length < 2) {
            modalMessageEvent.textContent = 'L\'événement est annulé pour le moment car il n\'y a pas assez de participants!'
            modalContent.hidden = true;
        }
        else {
            modalResponsable.textContent = responsable ? responsable['nom'] + ' ' + responsable['prenom'] + ' - Total croissants: ' + res['nb_croissant_total'] : 'Aucun'

            res['participants'].forEach((participant) => {
                let participantsContent = '<tr>\n'
                participantsContent += '<td>' + participant['nom'] + ' ' + participant['prenom'] + '</td>'
                participantsContent += '<td>' + participant['nb_croissant'] + '</td>'
                if (participant['is_present'] === '1') {
                    participantsContent += '<td><i class="fa-solid fa-check" style="color: #00ff00;"></i></td>\n'
                } else {
                    participantsContent += '<td><i class="fa-solid fa-xmark" style="color: #ff0000;"></i></td>\n'
                }
                participantsContent += '</tr>\n'

                modalParticipants.innerHTML += participantsContent
            })
        }

        let formValiderPresenceEventRadioOui = document.getElementById("present_oui");
        let formValiderPresenceEventRadioNon = document.getElementById("present_non");
        let formNbCroissants = document.getElementById('form_nb_croissant');

        formValiderPresenceEventRadioOui.checked = iAmPresent == 1;
        formValiderPresenceEventRadioNon.checked = iAmPresent == 0;

        formNbCroissants.value = myNbCroissant

    }).catch(err => {
        console.log(err);
    })
}

function displayMessages(res) {
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
}