<?php session_start(); if(!isset($_SESSION['me'])) header("Location: /index.php");?>
<!DOCTYPE html>
<html>
<head>
	<title>pingR</title>
	<?php include $_SERVER['DOCUMENT_ROOT']."/design/design";?>
	<?php include $_SERVER['DOCUMENT_ROOT']."/offline";?>
	<link rel="stylesheet" type="text/css" href="/cssoverride.css">
	<script type="text/javascript" src="pings.js"></script>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1" width=device-width height=device-height>
	<style type="text/css">
		footer > span{
			margin: 0 auto;
			display: block;
			position: relative;
			text-align: center;
			top: 40%;
		}
		
		#aghlogo{
			display: block;
			position: relative;
			top:50%;
			width: 24px;	
			left: 50%;
		}

		img.peoplephoto{
			width: 50px;
			height: 50px;
			border-radius: 27px;
			margin-left: 24px;
			margin-right: 14px;
		}
		img.online{
			border: 4px solid #2196F3;
		}
		img.offline{
			border: 4px solid rgba(33, 150, 243,0);	
		}
	</style>
</head>
<body>
	<header><h1>pingR</h1><div class="headercontrols"><span class="peoplename"></span><img class="profile" onmouseover="" onmouseout="" src="<?php echo $_SESSION['me']['imageurl'];?>"><a class="signout" href="/?signout">Sign out</a></div></header>
	<space>
	<card class="nopadding">
	<div class="card-padding">
	<h1>People</h1>
	<p>Here are your recent pings...</p>
	</div>
	<?php
	require $_SERVER['DOCUMENT_ROOT']."/apis/u.php";
	
	$pings = getRecentPings($_SESSION["me"]);

	if(count($pings)==0)
		echo "<p style=\"padding:24px;font-size:18px;\">No pings yet</p>";

	foreach($pings as $user)
	{
		$t = new DateTime($user['activity']);
		$x = new DateTime();
		$diff = $x->diff($t); 	
		$q = 0;
		if($diff->y + $diff->m + $diff->d + $diff->h + $diff->i)
			$q = 1;

		echo "\t<cardlist tabindex=0 onclick=\"window.location.href='message.php?u=".$user['userid']."';\"><img class='peoplephoto ".(($q) ? "offline" : "online")."' src='".$user['imageurl']."' style='display:inline-block;vertical-align:middle;'><span>".$user['fname']." ".$user['lname']."</span></cardlist>\n";
	}
	?>
	</card>

	<card class="nopadding">
	<div class="card-padding">
	<h1>Others</h1>
	<p>Strangers, not anymore...</p>
	<label style="width:30%;display:inline-block;" for="name">Search</label><input look="holo" style="width:60%" type="text" name="name" id="name" oninput="listPings();"><br />
	<p id="result"></p>
	</div>
	<div id="pinglist">
	</div>
	</card>
	</space>
	<footer>
	<span>Handcrafted with love by the techies at <a>MIT</a> and our awesome contributers.</span>
	<a id="aghlogo" href="https://github.com/RyanAfrish7/pingR" target="_blank"><img class="logo" width="24px" src="/resources/GitHub.png" /></a>
	</footer>
</body>
</html>