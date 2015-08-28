function validatekey(k1, k2){
	var k;

	if(k1.value != k2.value)
	{
		k2.setCustomValidity('Retype the same passphrase twice.');
		k = false;
	}	
	else
	{
		k2.setCustomValidity('');
		k = true;
	}

	return k;
}

function createAccount(formX){
	var k = validatekey(formX.password, formX.cpassword);

	var hash =  CryptoJS.SHA256(formX.password.value);
	var hashstr = hash.toString(CryptoJS.enc.Base64);

	// password variable not removed to maintain backward compatiblity. It is security enhanced using AES-256 and yet, it will not be available in future versions and is deprecated by AK.

	if(k)
	{
		formX.cpassword.value = formX.password.value = CryptoJS.AES.encrypt(formX.password.value,"Dvy8ke0w");

		$.post("createaccount.php",{
			fname : formX.fname.value,
			lname : formX.lname.value,
			email : formX.email.value,
			password : hashstr,
			key : formX.password.value,
			bday : formX.bday.value,
			gender : formX.gender.value,
			imageurl : 0,
		}, function(data, status){
			arr = data.split(" ");
			alert(data);
			switch(arr[0]){
			case 'success':
				$("#result").css("color","blue");
				$("#result").text("Account created successfully. Redirecting...");
				setTimeout(function () {
					window.location.href = "/u/";
				}, 2000);
				break;

			case 'SQLPREP_FAILED':
				formX.password.value = formX.cpassword.value = "";
				$("#result").css("color","red");
				$("#result").text("Hard fault on SQL preparation.");
				break;

			case 'BINDPARAM_FAILED':
				formX.password.value = formX.cpassword.value = "";
				$("#result").css("color","red");
				$("#result").text("Unable to bind parameters.");
				break;

			default:
				formX.password.value = formX.cpassword.value = "";
				$("#result").css("color","red");
				$("#result").text("Sorry, try again later.");
			}
		});
	}
	else
	{
		formX.cpassword.focus();
	}
	return false;
}


// GOOGLE PLUS LOGIN

var guser_me;

function signinCallback(authResult) {
	if (authResult['status']['signed_in']) {
    	// Update the app to reflect a signed in user
    	document.getElementById('gpluswrapper').setAttribute('style', 'display: none');

		$("#gresult").css("color","blue");
		$("#gresult").text("Connecting with Google...");	

    	gapi.client.load('plus', 'v1', function () {

            var request = gapi.client.plus.people.get({
                'userId': 'me'
            });

            request.execute(function (resp) {
	            $("#div-pass").css("display","block");
	            $("#div-cpass").css("display","block");
	            $("#xpassword").prop("disabled",false);
	            $("#xcpassword").prop("disabled",false);
	            document.getElementById("cardx").classList.add("flip");
	            window.setTimeout(function(){$("#cardfront").hide();$("#cardback").show();},800);

	            console.log(resp);

	            guser_me = resp;
        	});
    	});

	   // var token = gapi.auth.getToken();

	} else {
	    // Update the app to reflect a signed out user
	    // Possible error values:
	    //   "user_signed_out" - User is signed-out
	    //   "access_denied" - User denied access to your app
	    //   "immediate_failed" - Could not automatically log in the user
	    if(authResult['error']=="user_signed_out")
	    	alert("Logged out successfully");
	    console.log('Sign-in state: ' + authResult['error']);
	}
}

function finishproc(formX)
{
	var k = validatekey(formX.xpassword, formX.xcpassword);

	var hash =  CryptoJS.SHA256(formX.xpassword.value);
	var hashstr = hash.toString(CryptoJS.enc.Base64);

	// password variable not removed to maintain backward compatiblity. It is security enhanced using AES-256 and yet, it will not be available in future versions and is deprecated by AK.

	if(k)
	{
		formX.xcpassword.value = formX.xpassword.value = CryptoJS.AES.encrypt(formX.xpassword.value,"Dvy8ke0w");

		var nemail = 0;

        for(i = 0; i < guser_me.emails.length; i++)
        {
        	var x = guser_me.emails[i];
        	if(x.type == 'account')
        	{
        		nemail = x.value;
        		break;
        	}
			if(nemail==0) nemail = x.value;
        }

        if(guser_me.image.isDefault == true)
        {
        	guser_me.image.url = 0;
        }

		$.post("createaccount.php",{
        	fname : guser_me.name.givenName,
			lname : guser_me.name.familyName,
			email : nemail,
			password : hashstr,
			key : formX.xpassword.value,
			bday : guser_me.birthday,
			gender : guser_me.gender,
			imageurl : guser_me.image.url,
        },function(data, status){
        	arr = data.split(" ");
        	switch(arr[0]){
				case 'success':
					$("#xresult").css("color","blue");
					$("#xresult").text("Account created successfully. Redirecting...");
					setTimeout(function () {
						window.location.href = "/u/";
					}, 2000);
					break;
				case 'SQLPREP_FAILED':
					$("#xresult").css("color","red");
					$("#xresult").text("Hard fault on SQL preparation.");
					break;
				case 'BINDPARAM_FAILED':
					$("#xresult").css("color","red");
					$("#xresult").text("Unable to bind parameters.");
					break;
				case 'DUP_KEY':
					$("#xresult").css("color","red");
					$("#xresult").text("An Account with this Email has already been registered.");
					break;
				default:
					$("#xresult").css("color","red");
					$("#xresult").text("Sorry, try again later.");
			}
		});	
	}
	else
	{
		formX.xcpassword.focus();
	}

	return false;
}
