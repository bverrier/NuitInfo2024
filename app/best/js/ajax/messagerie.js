window.addEventListener("load", function () {
	addEvent();
});

function addEvent() {
	let buttonSend = document.getElementById('sendMessagerie')

	let titre = document.getElementsByClassName("modal-title")[0]
	if (titre !== null) {
		titre.innerHTML = "Envoyer un mail"
	}
	if (buttonSend !== null) {
		buttonSend.addEventListener('click', sendForm)
	}

	$('#dataTable').on('click', '.showMessage', function () {
		showMessage(this);
	});

	let button = document.getElementById('addButton')
	if (button !== null) {
		button.addEventListener('click', createForm)
	}

}

function createForm() {

	let formData = new FormData()
	formData.append("messagerie", "getForm")
	fetch('ajax/ajaxMessagerie.php', {
		method: "POST",
		body: formData
	}).then(res => {
		if (res.status !== 200) {
			throw new Error("Bad server response");
		}
		return (res.text());
	}).then(res => {
		let titre = document.getElementsByClassName("modal-title")[0]
		if (titre !== null) {
			titre.innerHTML = "Envoyer un mail"
		}
		let body = document.getElementsByClassName("modal-body")[0]
		if (body !== null) {
			body.innerHTML = res
			let buttonSend = document.getElementById('sendMessagerie')
			if (buttonSend !== null) {
				buttonSend.addEventListener('click', sendForm)
			}
		}
	}).catch(err => {
		console.log(err);
	})
}

function showMessage(button) {
	let tdElement = $(button).closest('tr').find('td');
	let message = tdElement[4].innerText;
	let titre = document.getElementsByClassName("modal-title")[0]
	if (titre !== null) {
		titre.innerHTML = "Mail"
	}

	let body = document.getElementsByClassName("modal-body")[0]
	if (body !== null) {
		body.innerHTML = message
	}

}

function sendForm() {
	let form = document.getElementById('formMessagerie')
	if (form !== null) {
		let formData = new FormData(form)
		formData.append("messagerie", "addMail")
		fetch('ajax/ajaxMessagerie.php', {
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
				let buttonClose = document.getElementById('closeMessagerie');
				if (buttonClose !== null) {
					buttonClose.addEventListener('click', function () {
						location.reload();
					}, false);
				}
			}
			if (id === null) {
				let form = document.getElementById("formMessagerie");
				if (form !== null) {
					form.reset();
				}
			}
		}).catch(err => {
			console.log(err);
		})
	}
}