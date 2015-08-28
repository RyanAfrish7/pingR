<?php

function time2str($ts)
{
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
}

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

	var_dump($ids);

	// prepare and bind
	if(!($stmt = $conn->prepare("SELECT userid,fname,lname,email,imageurl,activity FROM userlist WHERE userid IN ($in);")))
		die("SQLPREP_FAILED ".$conn->error);

	if(!$stmt->bind_param(str_repeat('i', count($ids)), ...$ids))
		die("BINDPARAM_FAILED".$conn->error);
	
	if(!$stmt->execute())
		die("EXEC_FAILED".$conn->error);
	
	if(!($result = $stmt->get_result()))
		die("RESULT FAILED".$conn->error);

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

		if(!	$conn->query("CREATE TABLE IF NOT EXISTS ".$tname." ( pingedat DATETIME NOT NULL ,  status VARCHAR(6) NOT NULL ,  message VARCHAR(1024) NOT NULL , PRIMARY KEY(pingedat) ) ENGINE = InnoDB;"))
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
	session_start();

	$me = $_SESSION['me'];

	if($uid==$me['userid']) return NULL;

	$arr = array($me['userid'],$uid);
	sort($arr, SORT_NUMERIC);

	$n1 = str_pad($arr[0], 4, "0", STR_PAD_LEFT); 
	$n2 = str_pad($arr[1], 4, "0", STR_PAD_LEFT); 
	$tname = "ping".$n1.$n2;

	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	// Create connection
	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error)
	    die("Connection failed: " . $conn->connect_error);

	// prepare and bind
	if(!($stmt = $conn->prepare("INSERT INTO $tname (pingedat,status,message) VALUES (?, ?, ?)")))
		die("SQLPREP_FAILED ".$conn->error);
	
	$time = date('Y-m-d H:i:s');
	$status = "0+".$me['userid'];

	if(!$stmt->bind_param("sss", $time, $status, $text))
		die("BINDPARAM_FAILED ".$stmt->error);
	
	if(!$stmt->execute())
		die("EXEC_FAILED ".$stmt->error);

	echo "success";

	$stmt->close();

	if($result = $conn->query("SELECT userid,recentpings FROM userlist WHERE userid = ".$me['userid']." OR userid = ".$uid.";"))
	{
		$ping1 = $result->fetch_assoc();
		$ping2 = $result->fetch_assoc();

		$rp1 = explode(",",$ping1['recentpings']);
		$rp2 = explode(",",$ping2['recentpings']);

		$x1 = $x2 = "";

		if($rp1[0] != $ping2['userid'])
		{
			if(in_array($ping2['userid'], $rp1))
			{
				array_unshift($rp1, $ping2['userid']);
				array_splice($rp1, 7);
				$rp1 = array_unique($rp1);
				$rp1 = array_pad($rp1, 7, 0);
			}
			else
			{
				array_unshift($rp1, $ping2['userid']);
				array_splice($rp1, 7);
			}
			$x1 = implode(",", $rp1);
			$conn->query("UPDATE userlist SET recentpings = '".$x1."' WHERE userid = ".$ping1['userid'].";");
		}
		if($rp2[0] != $ping1['userid'])
		{
			if(in_array($ping1['userid'], $rp2))
			{
				array_unshift($rp2, $ping1['userid']);
				array_splice($rp2, 7);
				$rp2 = array_unique($rp2);
				$rp2 = array_pad($rp2, 7, 0);
			}
			else
			{
				array_unshift($rp2, $ping1['userid']);
				array_splice($rp2, 7);
			}
			$x2 = implode(",", $rp2);
			$conn->query("UPDATE userlist SET recentpings = '".$x2."' WHERE userid = ".$ping2['userid'].";");
		}

		if($x1!=0 && $x2!=0)
		{
			if($ping1['userid']==$_SESSION['me']['userid'])
				$_SESSION['me']['recentpings'] = $x1;
			else
				$_SESSION['me']['recentpings'] = $x2;	
		}
	}

	if(!$conn->query("UPDATE userlist SET activity = '".date('Y-m-d H:i:s')."' WHERE userid = ".$me['userid'].";"))
		die("+ONLINE STAT FAILED TO UPDATE".$conn->error);

	$conn->close();
 	
}

function getmessage($ping,$me)
{

	$uid = $ping['userid'];

	if($uid==$me['userid']) return NULL;

	$arr = array($me['userid'],$uid);
	sort($arr, SORT_NUMERIC);

	$n1 = str_pad($arr[0], 4, "0", STR_PAD_LEFT); 
	$n2 = str_pad($arr[1], 4, "0", STR_PAD_LEFT); 
	$tname = "ping".$n1.$n2;

	require $_SERVER['DOCUMENT_ROOT']."/key.php";

	// Create connection
	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	// Check connection
	if ($conn->connect_error)
	    die("Connection failed: " . $conn->connect_error);

	if(!($result = $conn->query("SELECT pingedat,status,message FROM $tname ORDER BY pingedat DESC LIMIT 10")))
		die("QUERY FAILED");

	$data = "";

	$ping = getPing($_SESSION['me'],$uid,"no");

	while($row = $result->fetch_assoc())
	{
		$date = new DateTime($row['pingedat']);
		$data = "<div class=\"message\"><span class=\"people\">".((explode("+",$row['status'])[1] != $uid)?("You"/*$me['fname']." ".$me['lname']*/):($ping['fname']." ".$ping['lname']))."</span><p class=\"message\">".$row['message']."</p><span class=\"time\">".time2str($date->getTimestamp())."</span></div>".$data;
	}

	if(!$conn->query("UPDATE userlist SET activity = '".date('Y-m-d H:i:s')."' WHERE userid = ".$me['userid'].";"))
		die("+ONLINE STAT FAILED TO UPDATE".$conn->error);

	return $data;
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
		case 'getmessage':
			session_start();
			echo getmessage(getPing($_SESSION['me'],$_POST['uid'],'no'),$_SESSION['me']);
			break;
	}
}
?>