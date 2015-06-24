<?php
	session_start();
	if(file_exists($_SESSION['userid'].'/'.$_POST['userid'].'/contents'))
		echo file_get_contents($_SESSION['userid'].'/'.$_POST['userid'].'/contents');
	else
	{
		touch($_SESSION['userid'].'/'.$_POST['userid'].'/contents');
		echo "No messages found";
	}
?>