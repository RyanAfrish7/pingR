
function sendmessage(formX, puid){

	$.post("/u.php", {
		text: formX.text.value,
		callfunction: 'sendmessage',
		uid: puid
	});

}