<?php

function getRecentPings($me)
{
	if($me['status'] != 'OK') return NULL;
	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	// Create connection
	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error)
	    die("Connection failed: " . $conn->connect_error);

	$ids = explode(",",$me['recentpings']);
	$in = join(',', array_fill(0, count($ids), '?'));

	// prepare and bind
	if(!($stmt = $conn->prepare("SELECT userid,fname,lname,email,imageurl,activity FROM userlist WHERE userid IN ($in);")))
		die("SQLPREP_FAILED ".$conn->error);

	$stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
	$stmt->execute();
	$result = $stmt->get_result();

	$pings = array();

	while($row = $result->fetch_assoc())
	{
		array_push($pings,$row);
	}

	$stmt->close();
	$conn->close();

	return $pings;
}

function listPings($query)
{
	session_start();
	$me = $_SESSION['me'];

	if($me['status'] != 'OK') return NULL;
	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	// Create connection
	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error)
	    die("Connection failed: " . $conn->connect_error);

	// prepare and bind
	if(!($stmt = $conn->prepare("SELECT userid,fname,lname,email,imageurl,activity FROM userlist WHERE fname LIKE ? OR lname LIKE ? OR email LIKE ?")))
		die("SQLPREP_FAILED ".$conn->error);

	$query = $query."%";

	$stmt->bind_param("sss", $query, $query, $query);
	$stmt->execute();
	$result = $stmt->get_result();

	$pings = array();
	$data = "";
	while($row = $result->fetch_assoc())
	{
		if($row["userid"] == $me["userid"]) continue;
		array_push($pings,$row);
		$data .= "\t<cardlist tabindex=0 onclick=\"window.location.href='message.php?plus=true&u=".$row['userid']."';\"><img class='peoplephoto' src='".$row['imageurl']."' style='display:inline-block;vertical-align:middle;'><span>".$row['fname']." ".$row['lname']."</span></cardlist>\n";
	}

	if(count($pings)==0) $data = "0";

	$stmt->close();
	$conn->close();

	return $data;
}

function getPing($me, $uid, $stat)
{
	if($me['status'] != 'OK') return "NULL";

	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	// Create connection
	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error)
	    die("Connection failed: " . $conn->connect_error);

	if($stat == "yes")
	{
		if(!(($uid<10000)&&($uid>0))) return NULL;
		if($uid==$me['userid']) return NULL;

		$arr = array($me['userid'],$uid);
		sort($arr, SORT_NUMERIC);

		$n1 = str_pad($arr[0], 4, "0", STR_PAD_LEFT); 
		$n2 = str_pad($arr[1], 4, "0", STR_PAD_LEFT); 
		$tname = "ping".$n1.$n2;

		if(!	$conn->query("CREATE TABLE IF NOT EXISTS ".$tname." ( pingedat DATETIME NOT NULL ,  status INT NOT NULL ,  message VARCHAR(1024) NOT NULL ) ENGINE = InnoDB;"))
			die("INVALID SQL : ".$conn->error);

	}

	// prepare and bind
	if(!($stmt = $conn->prepare("SELECT userid,fname,lname,email,imageurl,activity FROM userlist WHERE userid=?;")))
		die("SQLPREP_FAILED ".$conn->error);

	$stmt->bind_param("s", $uid);
	$stmt->execute();
	$result = $stmt->get_result();

	if($result->num_rows!=1)
	{	
		$stmt->close();
		$conn->close();

		return array($result->num_rows,$uid);;
	}

	$ping = $result->fetch_assoc();
	
	$stmt->close();
	$conn->close();

	return $ping;
}

function sendmessage($text, $uid)
{
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
}

if(isset($_POST['callfunction']))
{
	switch($_POST['callfunction'])
	{
		case 'listPings':
			echo listPings($_POST['querydata']);
			break;
		case 'sendmessage':
			echo sendmessage($_POST['text'], $_POST['uid']);
			break;
	}
}
?>