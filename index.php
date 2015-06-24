<?php session_start(); if(isset($_SESSION['me'])) header("Location: /u/");?>
<!DOCTYPE html>
<html>
<head>
	<title>pingR</title>
	<?php include $_SERVER['DOCUMENT_ROOT']."/design/design";?>
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/aes.js"></script>
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/components/enc-base64-min.js"></script>
	<script type="text/javascript" src="http://l2.io/ip.js?var=myip"></script>
	<script type="text/javascript" src="signin.js"></script>
	<script type="text/javascript" src="reset.js"></script>
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

		#signin-wrapper{
			transform-style: preserve-3d;
		}

		#reset-key{
			display: none;
			transform-style: preserve-3d;
			transform: rotateY(180deg);
		}

		#ccode-reset{
			display: none;
		}

		#pname{
			color: black;
			font-family: Roboto;
			font-weight: bold;
		}
	</style>

	<script>
	var state = 0;


	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
</head>
<body>
	<div id="fb-root"></div>
	<header><h1>pingR</h1></header>
	<space>
	<card>
		<h1>Welcome</h1>
		<p><b>pingR</b> is a free social instant messaging service. It is a more polished version of myMessenger.</p>
		<p>Get connected to your friends, family and others. Just have the fun of chatting with your friends in a Material Designed environment. This website is under <span class="smallcaps">beta testing.</span></p>
	</card>
	<card id="card-signin">

		<div id="signin-wrapper">
		<h1>Get Started</h1>
		<h2>Sign in</h2>
		<form action="na.php" method="POST" onsubmit="return signin(this);">
		<label style="width:30%;display:inline-block;" for="email">Email</label><input look="holo" style="width:60%" type="email" name="email" id="email" required>
		<label style="width:30%;display:inline-block;" for="password">Passphrase</label><input look="holo" style="width:60%" type="password" name="password" id="password" minlength=8 required><br />
		<input type="submit" value="" style="width: 100%"><br />
		</form>
		Forgot password? <button class="text" onclick="changeWrapper(document.getElementById('card-signin'));">Reset you password here</button><br />
		Have no account? <a href="/signup.php">Get one here</a>.<br /><p id="result"></p>
		</div>

		<div id="reset-key">
		<h1>Don't worry</h1>
		<h2>Reset your Passphrase</h2>
		<form action="mailkey.php" method="POST" onsubmit="if(validateemail(this)); return false;">
		<label style="width:30%;display:inline-block;" for="xemail">Email</label><input look="holo" style="width:60%" type="email" name="xemail" id="xemail" required><br />
		<label style="width:30%;display:inline-block;" for="cxemail">Retype your Email</label><input look="holo" style="width:60%" type="email" name="cxemail" id="cxemail" required><br />
		<input type="submit" value="" style="width: 100%"><br />
		</form>
		<p id="res"></p>
		<button class="text" onclick="changeWrapper(document.getElementById('card-signin'));">Go to Sign in</button><br />
		</div>


		<div id="ccode-reset">
		<h1>Don't worry</h1>
		<h2>Reset your Passphrase</h2>
		<p>Hello <span id="pname"></span></p>
		<form action="na.php" method="POST" onsubmit="resetpassword(this); return(false);">
		<label style="width:30%;display:inline-block;" for="code">Confirmation code</label><input look="holo" style="width:60%" type="text" name="code" id="code" required><br />
		<label style="width:30%;display:inline-block;" for="newpass">New passphrase</label><input look="holo" style="width:60%" type="password" name="newpass" id="newpass" minlength=8 required><br />
		<label style="width:30%;display:inline-block;" for="cnewpass">Retype passphrase</label><input look="holo" style="width:60%" type="password" name="cnewpass" id="cnewpass" minlength=8 required><br />
		<input type="submit" value="" style="width: 100%"><br />
		<p id="resultx"></p>
		<button class="text" onclick="changeWrapper(document.getElementById('card-signin'));">Go to Sign in</button><br />
		</form>
		</div>

	</card>
	<card>
		<h1>What's new?</h1>
		<h2>For Users</h2>
		<ul>
		<li>Improved layout</li>
		<li>Enhanced messaging system</li>
		<li>Easier and quicker sign-in using Google Plus</li>
		<li>Improved Security and Privacy</li>
		</ul>
		<p>Wanna help us? Spread the word for the new Indian instant messaging service <a href="http://pingr.cf">pingR</a>. </p><div class="fb-share-button" data-href="http://pingr.cf" data-layout="button_count"></div>
		<h2>For designers</h2>
		<ul>
		<li>Scrollable headers</li>
		<li>Footer</li>
		<li>OAuth 2.0 supporting Google plus Authentication</li>
		<li>Passwords transferred are now encrypted using SHA-256</li>
		<li>More data about users are stored</li>
		</ul>
		<p>Wanna contribute us? Check out our repository on <a href="https://github.com/RyanAfrish7/pingR">GitHub</a>. </p>
	</card>
	</space>
	<footer>
	<span>Handcrafted with love by the techies at <a>MIT</a> and our awesome contributers.</span>
	<a id="aghlogo" href="https://github.com/RyanAfrish7/pingR" target="_blank"><img class="logo" width="24px" src="/resources/GitHub.png" /></a>
	</footer>
</body>
</html>