<?php 
	session_start();
	if(!isset($_SESSION['me'])) header("Location: /index.php");

	require $_SERVER['DOCUMENT_ROOT']."/apis/u.php";	
	$pings = getRecentPings($_SESSION["me"]);
	$ping = getPing($_SESSION["me"], $_GET['u'], ((isset($_GET['plus'])) ? 'yes' : 'no'));

	?>
<!DOCTYPE html>
<html>
<head>
	<title>pingR</title>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1" width=device-width height=device-height>
	<?php include $_SERVER['DOCUMENT_ROOT']."/design/design";?>
	<link rel="stylesheet" type="text/css" href="widedevice.css">
	<link rel="stylesheet" type="text/css" href="longdevice.css">
	<link rel="stylesheet" type="text/css" href="message.css">
	<link rel="stylesheet" type="text/css" href="/cssoverride.css">
	<script type="text/javascript" src="message.js.php?u=<?php echo $_GET['u']; ?>"></script>
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
		
		@media only screen and (min-device-width: 200px) and (max-device-width: 736px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait) { 
			div.wide{
				display: none;
			}
			div.long{
				display: block;
			}
		}

		@media only screen and (min-device-width: 200px) and (max-device-width: 736px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: landscape) { 
			space.override{
				max-width: 80vw;
				width: 80vw;
			}
			div.wide{
				display: block;
			}
			div.long{
				display: none;
			}
		}

		@media only screen and (min-device-width: 736px) { 
			space.override{
				max-width: 1000px;
				width: 80vw;
			}
			div.wide{
				display: block;
			}
			div.long{
				display: none;
			}
		}
	</style>
</head>
<body>
	<header><h1>pingR</h1><div class="headercontrols"><span class="peoplename"></span><img class="profile" onmouseover="" onmouseout="" src="<?php echo $_SESSION['me']['imageurl'];?>"><a class="signout" href="/?signout">Sign out</a></div></header>
	<space class="override">

	<div class="wide">
	<card class="nopadding">
	<table id="main">
	<tbody>
	<tr><td class="backarrow"><a class="backarrow" href="/u/"><img class="image backarrow" src="/resources/arrowleft.png"></a></td><td class="peoplename"><h1 class="peoplename"><?php echo $ping['fname']." ".$ping['lname'];?></h1></td><td rowspan="20" class="messagecell">
	<div class="messagecell">
	<div class="messagespace">

	</div>
	<div class='mbox'><form method="POST" name="wideform" action="/na.php" onsubmit="return sendmessage(this);"><input class="text" type="text" name="text" id="text"><img class="send" src="/resources/send.png" onclick=""></form></div>
	</div>

	
	</td></tr>
	<tr><td colspan="2" class="imagecell"><img class="image" src="<?php echo $ping['imageurl'];?>"></td></tr>
	<tr><td colspan="2" class="opings"><h2 class="opings">Other pings</h2><div id="opings">

	<?php
	$pings = getRecentPings($_SESSION["me"]);
	
	foreach($pings as $user)
	{
		$t = new DateTime($user['activity']);
		$x = new DateTime();
		$diff = $x->diff($t); 	
		$q = 0;
		if($diff->y + $diff->m + $diff->d + $diff->h + $diff->i)
		$q = 1;

		echo "\t<cardlist tabindex=0 onclick=\"window.location.href='message.php?u=".$user['userid']."';\"><img class='peoplephoto ".( ($q) ? "offline" : "online" )."' src='".$user['imageurl']."' style='display:inline-block;vertical-align:middle;'><span>".$user['fname']." ".$user['lname']."</span></cardlist>\n";
	}
	?>
	</div>
	</td></tr>
	</tbody>
	</table>
	</card>
	</div>

	<div class="long">
	<card class="nopadding" style="margin-bottom:0px;">
	<table id="maindev">
	<tbody>
	<tr><td class="backarrow"><a class="backarrow" href="/u/"><img class="image backarrow" src="/resources/arrowleft.png"></a></td><td class="peoplename"><h1 class="peoplename"><?php echo $ping['fname']." ".$ping['lname'];?></h1></td></tr>
	<tr><td colspan="2" class="imagecell"><div class="imagecell"><div style="background: url('<?php echo $ping['imageurl'];?>'); background-repeat: no-repeat;background-size: cover;	" class="image photo"></div></div></td></tr>
	<tr><td colspan="2" class="messageheader"><h1 class="messageheader">Pings</h1></td></tr>
	<tr><td colspan="2" class="messagecell">
	<div class="messagespace">
	
	</div>
	</td></tr>
	</tbody>
	</table>
	<div style="border-top: 1px solid rgba(0, 0, 0, 0.2);"><form method="POST" name="longform" action="/na.php" onsubmit="return sendmessage(this);"><input class="text" type="text" name="text" id="text"><img class="send" src="/resources/send.png" onclick=""></form></div>
	</card>
	</div>

	</space>
</body>
</html>