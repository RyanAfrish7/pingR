<?php
	session_start();

	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	// Create connection
	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error)
	    die("Connection failed: " . $conn->connect_error);

	// prepare and bind
	if(!($stmt = $conn->prepare("SELECT firstname,lastname FROM userlist WHERE email=lower(?)")))
		die("SQLPREP_FAILED ".$conn->error);
	
	$email = $_POST['email'];

	if(!$stmt->bind_param("s", $email))
		die("BINDPARAM_FAILED ".$stmt->error);
	
	if(!$stmt->execute())
		die("EXEC_FAILED ".$stmt->error);

	if(!$stmt->bind_result($fname, $lname))
		die("BINDRESULT_FAILED ".$stmt->error);

	if(!$stmt->fetch())
		die("INVALID_EMAIL");
	

	$headers = "From: noreply@pingr.cf\r\n";
	$headers = $headers."MIME-Version: 1.0\r\nContent-Type: text/html; charset=ISO-8859-1\r\n";
	//$code = rand(11111,99999); // NOT IN DEBUG VERSION
	$code = 18119;
	mail($email, "Passphrase reset - pingR", "<html><body><h1>pingR - Passphrase reset query</h1>It seems you have requested for reset of passphrase in pingR. You may ignore this without any security concern if it is not you who placed this query. Check out the confirmation code: <b>".$code."</b></body></html>",$headers);
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['code'] = $code;
	echo "success&".$fname."&".$lname;
?>