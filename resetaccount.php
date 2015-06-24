<?php
	session_start();

	if($_SESSION["code"]==$_POST['code'])
	{
		require $_SERVER['DOCUMENT_ROOT']."/key.php";

		$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		}

		// prepare and bind
		if(!($stmt = $conn->prepare("UPDATE userlist SET password=?, hashkey=? WHERE email=?")))
			die("SQLPREP_FAILED ".$conn->error);
		
		if(!$stmt->bind_param("sss", $password, $hashkey, $email))
			die("BINDPARAM_FAILED".$stmt->error);

		$email = $_SESSION['email'];
		$password = $_POST['password'];
		$hashkey = $_POST['key'];//mcrypt_decrypt('Dvy8ke0w',$_POST['key']);

		if(!($stmt->execute()))
		{
			die("EXEC_FAILED ".$stmt->error);
		}

		$stmt->close();
		$conn->close();

		echo "success&";
		session_destroy();
	}
	else
		echo "INVALID_CODE&";



?>