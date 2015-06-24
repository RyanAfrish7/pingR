<?php

	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);


	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	// prepare and bind
	if(!($stmt = $conn->prepare("INSERT INTO userlist (fname, lname, email, password, hashkey, birthday, gender, created, imageurl, activity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")))
		die("SQLPREP_FAILED ".$conn->error);
	
	if(!$stmt->bind_param("sssssssss", $fname, $lname, $email, $password, $hashkey, $bday, $gender, $createddate, $imageurl, $activity))
		die("BINDPARAM_FAILED".$stmt->error);

	// set parameters and execute
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$hashkey = $_POST['key'];//mcrypt_decrypt('Dvy8ke0w',$_POST['key']);
	$bday = (isset($_POST['bday'])) ? $_POST['bday'] : '0000-00-00';
	$gender = (isset($_POST['gender'])) ? $_POST['gender'] : 0;
	$createddate = date('Y-m-d H:i:s');
	$imageurl = ($_POST['imageurl']!=0) ? $_POST['imageurl'] : "/resources/noprofile.png";
	$activity = date('Y-m-d H:i:s');

	if(!($stmt->execute()))
	{
		if($conn->errno == 1062){
			die("DUP_KEY");
		}
		die("EXEC_FAILED ".$stmt->error);
	}

	echo "success";

	$stmt->close();
	$conn->close();

?>