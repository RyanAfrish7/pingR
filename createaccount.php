<?php
	
	$imgfile = $_POST['imageurl'];

	if(isset($_FILES['photo']) && $_FILES['photo']['tmp_name']==none)
	{
		$uploaddir = '/userdata/';
		$uploadfile = $uploaddir . md5($_POST['email']);
		$uploadfileext =  ".tmp." . pathinfo($_FILES['photo']['tmp_name'], PATHINFO_EXTENSION);

		if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
			$im = new Imagick( $uploadfile . $uploadfileext);

			$im->setCompression(Imagick::COMPRESSION_JPEG); 
			$im->setCompressionQuality(60);
			$im->setImageFormat('jpeg');

			$im->resizeImage(192, 192, imagick::FILTER_LANCZOS, 1);

			$im->writeImage($uploadfile."jpg"); 
			$im->clear(); 
			$im->destroy();

			$imgfile = $uploadfile . "jpg";

		} else {
			// Possibly file upload attack
		}
	}

	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// prepare and bind
	if(!($stmt = $conn->prepare("INSERT INTO userlist (fname, lname, email, password, hashkey, birthday, gender, created, imageurl, activity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")))
		die("SQLPREP_FAILED ".$conn->error);
	
	if(!$stmt->bind_param("ssssssssss", $fname, $lname, $email, $password, $hashkey, $bday, $gender, $createddate, $imageurl, $activity))
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
	$imageurl = ($imgfile!=0) ? $imgfile : "/resources/noprofile.png";
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