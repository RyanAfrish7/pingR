<?php
	require 'mglobal.php';
	if (session_status() == PHP_SESSION_NONE) session_start();

	if(!isset($_SESSION['userid']))
		header("Location: ../");
	$usrid = array($_GET['u']);
	$usrarr = fetch_users($usrid);
	$usr = $usrarr[$_GET['u']];
?>
<!DOCTYPE html>
<html>
<head>
	<title>myMessenger</title>
	<link rel="stylesheet" type="text/css" href="mydesignx.css">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1" width=device-width height=device-height>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script type="text/javascript">
	var uname='Err:121';
	function showlogout(x)
	{
		uname = x.innerHTML;
		x.innerHTML="Sign Out";
	}
	function update()
	{
		$.post("get.php",
		{
			userid: <?php echo $usr['userid']; ?>
		},
		function(data, status){
			document.getElementById('message').innerHTML = data;
			document.getElementById('message').scrollTop = document.getElementById('message').scrollHeight;
		});
	}
	function sendMessage () {
        $.post("post.php",{
          userid: <?php echo $usr['userid']; ?>,
          message: document.getElementById('msg').value,
        },
        function(data,status){
            update();
          	document.getElementById('msg').value = '';  
        });
    }
    setInterval(function(){ update(); }, 300);
	</script>
	
</head>
<body>
<header class='mheader'><h1 class="mheader">myMessenger<span class='uN' id='uName' onmouseover="showlogout(this);" onclick="window.location.href='../index.php?result=success';" onmouseout="this.innerHTML=uname"><?php
if (session_status() == PHP_SESSION_NONE) session_start();
echo $_SESSION['username'];
?></span></h1></header>
<div class='content'><div class="card" id='card1' style='height:81vh'><table width="100%" height=100%><tr><td rowspan="2" class='details'>
	<div class='people' style="font-size:24px; "><div class='peoplename'><?php
	echo $usr['username'];
	?></div></div><a style="text-align:right;padding-left:20px" href='index.php'>back</a>
	</div></td><td class='message'><h3 class='card'>Chats</h3><div name='message' class='message' id='message'><?php
	if(!is_dir($_SESSION['userid'].'/'.$_GET['u']))
		mkdir($_SESSION['userid'].'/'.$_GET['u']);
	if(!is_dir($_GET['u'].'/'.$_SESSION['userid']))
		mkdir($_GET['u'].'/'.$_SESSION['userid']);
	if((include($_SESSION['userid'].'/'.$_GET['u'].'/contents')) == FALSE)
		echo 'No messages found';
	?>
	</div></td></tr><tr><td><form method="post" action='NA.php' onsubmit="sendMessage(); return false;"><input type='text' name='msg' id='msg' class='msg' required></form><div class='comment'>Tap Enter to send</div>
</td></tr></table></div></div>
</body>
</html>