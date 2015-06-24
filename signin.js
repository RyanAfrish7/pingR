function signin(thisform){

	var hash =  CryptoJS.SHA256(thisform.password.value);
	var hashstr = hash.toString(CryptoJS.enc.Base64);

	thisform.password.value = hashstr;
	
	$("#result").css("color","blue");
	$("#result").text("Signing In...");

	$.post("signin.php",{
		email : thisform.email.value,
		password : thisform.password.value,
	}, function(data, status){
		var arr = data.split(" ");
		switch(arr[0]){
		case 'success':
			$("#result").css("color","blue");
			$("#result").text("Signed in successfully. Redirecting...");
			setTimeout(function () {
				window.location.href = "/u/";
			}, 500);
			break;
		case 'SQLPREP_FAILED':
			thisform.password.value = "";
			$("#result").css("color","red");
			$("#result").text("Hard fault on SQL preparation.");
			break;
		case 'BINDPARAM_FAILED':
			thisform.password.value = "";
			$("#result").css("color","red");
			$("#result").text("Unable to bind parameters.");
			break;
		case 'NO_MATCH':
			thisform.password.value = "";
			thisform.password.focus();
			$("#result").css("color","red");
			$("#result").text("Incorrect username or password.");
			break;
		default:
			thisform.password.value = "";
			alert(data);
			$("#result").css("color","red");
			$("#result").text("Sorry, try again later.");
		}
	});
	return false;
}