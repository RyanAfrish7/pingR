<?php
	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	// Create connection
	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error)
	    die("Connection failed: " . $conn->connect_error);

	// prepare and bind
	if(!($stmt = $conn->prepare("SELECT userid,fname,lname,recentpings FROM userlist WHERE email=lower(?) AND password=?")))
		die("SQLPREP_FAILED ".$conn->error);
	
	$email = $_POST['email'];
	$password = $_POST['password'];

	if(!$stmt->bind_param("ss", $email, $password))
		die("BINDPARAM_FAILED ".$stmt->error);
	
	if(!$stmt->execute())
		die("EXEC_FAILED ".$stmt->error);

	if(!$stmt->bind_result($uid, $fname, $lname, $recentpings))
		die("BINDRESULT_FAILED ".$stmt->error);

	if(!$stmt->fetch())
		die("NO_MATCH");
	else 
	{
		echo "success";

		$stmt->close();

		if(!$conn->query("UPDATE userlist SET activity = '".date('Y-m-d H:i:s')."' WHERE userid = ".$uid.";"))
			die("SQL_FAILED ".$conn->error);

		$conn->close();

	}
 	
	session_start();

	$_SESSION['me'] = array('userid' => $uid, 'fname' => $fname, 'lname' => $lname, 'id' => $uid, 'email' => $email, 'status' => 'OK', 'recentpings' => $recentpings);
?>