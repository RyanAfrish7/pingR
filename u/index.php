<?php session_start(); if(!isset($_SESSION['me'])) header("Location: /index.php");?>
<!DOCTYPE html>
<html>
<head>
	<title>pingR</title>
	<?php include $_SERVER['DOCUMENT_ROOT']."/design/design";?>
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
			border-radius: 25px;
			margin-left: 24px;
			margin-right: 14px;
		}
	</style>
</head>
<body>
	<header><h1>pingR</h1></header>
	<space>
	<card class="nopadding">
	<div class="card-padding">
	<h1>People</h1>
	<p>Here are your recent pings...</p>
	</div>
	<?php
	require $_SERVER['DOCUMENT_ROOT']."/apis/u.php";
	
	$pings = getRecentPings($_SESSION["me"]);

	foreach($pings as $user)
		echo "\t<cardlist tabindex=0 onclick=\"window.location.href='message.php?u=".$user['userid']."';\"><img class='peoplephoto' src='".$user['imageurl']."' style='display:inline-block;vertical-align:middle;'><span>".$user['fname']." ".$user['lname']."</span></cardlist>\n";
	?>
	<cardlist tabindex=0><img class="peoplephoto" src="test.jpg" style="display:inline-block;vertical-align:middle;"><span>Afrish Khan S</span></cardlist>
	<cardlist tabindex=0><img class="peoplephoto" src="test1.jpg" style="display:inline-block;vertical-align:middle;"><span>Aryan</span></cardlist>
	<cardlist tabindex=0><img class="peoplephoto" src="test2.png" style="display:inline-block;vertical-align:middle;"><span>Karthik</span></cardlist>
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