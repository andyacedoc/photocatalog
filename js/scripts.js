function sendRequest(url, emptypost, formssite)
{
	//alert("Hi!");
	let xhr = new XMLHttpRequest();
    let formData = new FormData(document.forms.logreg_form);
    xhr.open('POST', url, true);
    xhr.onload = function () // функция обработки ответа сервера
    {
        if (xhr.status == 200) //проверяем код состояния 200 - OK
        {
			document.getElementById("inreg").innerHTML = xhr.response; //заменяем форму на ответ сервера
            if (xhr.response == "reloadpage") {
				location.reload();
            }
        }
    };
	if (emptypost == "emptypost") {
		xhr.send(); //посылаем запрос без данных
	} else {
		xhr.send(formData); //посылаем данные методом POST
	}
}

function sendRequestPhoto(url, emptypost, formssite)
{
	let xhr = new XMLHttpRequest();
    let formData = new FormData(document.forms.edit_form);
    xhr.open('POST', url, true);
    xhr.onload = function () // функция обработки ответа сервера
    {
        if (xhr.status == 200) //проверяем код состояния 200 - OK
        {
			document.getElementById("edit").innerHTML = xhr.response; //заменяем форму на ответ сервера
            if (xhr.response == "reloadpage") {
				location.reload();
            }
        }
    };
	if (emptypost == "emptypost") {
		xhr.send(); //посылаем запрос без данных
	} else {
		xhr.send(formData); //посылаем данные методом POST
	}
}

function showtEditPhotoForm(idphoto, url)
{
	var myModal = new bootstrap.Modal(document.getElementById('editphoto'), {keyboard: false});
	myModal.show();
	document.getElementById("idPhotoFound").value = idphoto;
	sendRequestPhoto(url);
}