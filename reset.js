function changeWrapper(cardX){
	if(cardX.classList.contains("flipback"))
	{
		cardX.classList.remove("flipback");
		cardX.classList.remove("flip");
		cardX.classList.add("noflip");
	}
	else
	{
		cardX.classList.remove("noflip");
		cardX.classList.toggle("flip");
	}	
	
	if(!state) window.setTimeout(function(){$("#signin-wrapper").hide();$("#reset-key").show();},800);
	else window.setTimeout(function(){$("#ccode-reset").hide();$("#signin-wrapper").show();$("#reset-key").hide();},800);

	state = ~state;
}

function validateemail(formX){
	if(formX.xemail.value != formX.cxemail.value)
	{
		$("#res").css('color','red');
		$("#res").text("Looks like a typo in your Email.");
		return false;
	}
	else
	{
		$.post("mailkey.php",{
			email: document.getElementById("xemail").value,
		},function(data, status){
			arr = data.split('&');
			if((status=="success"))
			{
				if(arr[0] == "success")
				{
					$("#card-signin").removeClass("flip");
					$("#card-signin").addClass("flipback");
					window.setTimeout(function(){$("#ccode-reset").show();$("#reset-key").hide();},800);

					$("#pname").text(arr[1] + " " + arr[2]);
				}
			}
		});
	}
	return true;
}

function validatekey(key1, key2){
	var k;

	if(key1.value != key2.value)
	{
		key2.setCustomValidity('Retype the same passphrase twice.');
		k = false;
	}	
	else
	{
		key2.setCustomValidity('');
		k = true;
	}

	return k;
}

function resetpassword (formX) {
	var k = validatekey(formX.newpass, formX.cnewpass);

	var hash =  CryptoJS.SHA256(formX.newpass.value);
	var hashstr = hash.toString(CryptoJS.enc.Base64);

	if(k)
	{
		formX.newpass.value = formX.cnewpass.value = CryptoJS.AES.encrypt(formX.newpass.value,"Dvy8ke0w");
		$.post("resetaccount.php",{
			code : formX.code.value,
			password : hashstr,
			key : formX.newpass.value,
		}, function(data, status){
			arr = data.split("&");
			switch(arr[0]){
			case 'success':
				$("#resultx").css("color","blue");
				$("#resultx").text("Passphrase resetted. Signing you in...");
				setTimeout(function () {
					window.location.href = "/u/";
				}, 2000);
				break;

			case 'SQLPREP_FAILED':
				formX.newpass.value = formX.cnewpass.value = "";
				$("#resultx").css("color","red");
				$("#resultx").text("Hard fault on SQL preparation.");
				break;

			case 'BINDPARAM_FAILED':
				formX.newpass.value = formX.cnewpass.value = "";
				$("#resultx").css("color","red");
				$("#resultx").text("Unable to bind parameters.");
				break;
			case 'INVALID_CODE':
				formX.code.value = "";
				$("#resultx").css("color","red");
				$("#resultx").text("Confirmation code not matched...");
				break;
			default:
				alert(data);
				formX.newpass.value = formX.cnewpass.value = "";
				$("#resultx").css("color","red");
				$("#resultx").text("Sorry, try again later.");
			}
		});
	}
}
