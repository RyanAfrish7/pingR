<?php
	session_start();
	if(!isset($_SESSION['userid']))
		header("Location: ../");
?>
<!DOCTYPE html>
<html>
<head>
	<title>myMessenger</title>
	<link rel="stylesheet" type="text/css" href="mydesign.css">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1" width=device-width height=device-height>
	<script type="text/javascript">
	var uname='Err:121';
	function showlogout(x)
	{
		uname = x.innerHTML;
		x.innerHTML="Sign Out";
	}
	</script>
</head>
<body>
<header class='mheader'><h1 class="mheader">myMessenger<span class='uN' id='uName' onmouseover="showlogout(this);" onclick="window.location.href='../index.php?result=success';" onmouseout="this.innerHTML=uname"><?php
if (session_status() == PHP_SESSION_NONE)  session_start();
echo $_SESSION['username'];
?></span></h1></header>
<div class='content'><div class="card" id='card1'><h3 class='card'>Friends</h3><p id='cnt' class="card">Happiness is annoying friends with 'aahaan...'</p>
<?php
	require 'mglobal.php';
	$uidlist  = scandir($_SESSION['userid'].'/');
	unset($uidlist[0]);
	unset($uidlist[1]);
	if(count($uidlist)>0)
	{
		$usrs=fetch_users($uidlist);
		foreach ($usrs as $people) {
			echo "<div class='cardlist'><div class='people' onclick='window.location.href=\"message.php?u=".$people['userid']."\"'><div class='peoplename'>".$people['username']."</div><a class='peoplemail' href='mailto:".$people['email']."'>".$people['email']."</a></div></div>";
		}
	}else{
		echo "<p class='card' style='padding-bottom:20px;'>Don't feel lonely. Get some friends below</p>";
	}
	echo "</div><div class='card' style='margin-top:30px' id='card1'><h3 class='card'>More Friends</h3><p class='card'>Click the people you want to add to your chatlist.</p>";

	$usrs = fetch_restusers($uidlist);
	if($usrs)
	foreach ($usrs as $people) {
		echo "<div class='cardlist'><div class='people' onclick='window.location.href=\"message.php?new=true&u=".$people['userid']."\"'><div class='peoplename'>".$people['username']."</div><a class='peoplemail' href='mailto:".$people['email']."'>".$people['email']."</a></div></div>";
	}
	echo "<div class='cardlist'><div class='people'><div class='peoplename'>Invite Others</div></div></div>";
?></div>
</body>
</html>