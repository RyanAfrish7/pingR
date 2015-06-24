<?php session_start(); if(isset($_SESSION['me'])) header("Location: /u/");?>
<!DOCTYPE html>
<html>
<head>
	<title>pingR</title>
	<?php include $_SERVER['DOCUMENT_ROOT']."/design/design";?>
	<script src="https://apis.google.com/js/client:platform.js" async defer></script>
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/aes.js"></script>
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/components/enc-base64-min.js"></script>
	<script type="text/javascript" src="/signup.js"></script>

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
			position: relative;s
			top:50%;
			width: 24px;	
			left: 50%;
		}

		#cardfront{
			transform-style: preserve-3d;
		}

		#cardback{
			display: none;
			transform-style: preserve-3d;
			transform: rotateY(180deg);
		}

		#div-fname, #div-lname, #div-email, #div-bday, #div-gender, #div-pass, #div-cpass{
			display: none;
		}

	</style>
	<script type="text/javascript">
		
	</script>
</head>
<body>
	<header><h1>pingR</h1></header>
	<space>
	<card id="cardx">
		<div id="cardfront">
		<h1>Get Started</h1>
		<div id="gpluswrapper" class="wrapper">
		<h2>Sign up with Google+</h2>
		<p>Signing up with Google+ saves you the time of filling lengthy forms.</p><br />
		<span id="signinButton">
			<span
		    class="g-signin"
		    data-callback="signinCallback"
		    data-clientid="1057985597831-sioopfgq2k0d2i4klbm87s88m0949umo.apps.googleusercontent.com"
		    data-cookiepolicy="single_host_origin"
		    data-scope="profile email">
	  		</span>
		</span>
		</div>
		<p id="gresult"></p>

		<div id="defsignupwrapper" class="wrapper">
		<h2>Sign up</h2>
		<form method="POST" action="createaccounts.php" name="signupp" onsubmit="return createAccount(this);">
		<label style="width:30%;display:inline-block;" for="fname">First name</label><input look="holo" style="width:60%" type="text" name="fname" id="fname" minlength=4 required><br />
		<label style="width:30%;display:inline-block;" for="lname">Last name</label><input look="holo" style="width:60%" type="text" name="lname" id="lname" required><br />
		<label style="width:30%;display:inline-block;" for="email">Email</label><input look="holo" style="width:60%" type="email" name="email" id="email" required><br />
		<label style="width:30%;display:inline-block;" for="bday">Birthday</label><input type="date" name="bday" id="bday" min="1970-01-01" max="<?php echo date('d-m-Y'); ?>" value="2000-01-01" required/><br />
		<label style="width:30%;display:inline-block;" for="gender">Gender</label><select look="holo" name="gender" id="gender" required><option>Male</option><option>Female</option><option>Unspecified</option></select><br />
		<label style="width:30%;display:inline-block;" for="password">Passphrase</label><input look="holo" style="width:60%" type="password" name="password" id="password" minlength=8 onchange="validatekey(this,document.getElementById('cpassword'));" required><br />
		<label style="width:30%;display:inline-block;" for="cpassword">Confirm passphrase</label><input look="holo" style="width:60%" type="password" name="cpassword" id="cpassword" onchange="validatekey(document.getElementById('password'),this);" required><br />
		<input type="submit" value="" style="width: 100%"><br />
		</form>
		</div>

		Remember having an account already, <a href="/index.php">Sign in here</a>.<br />
		<p id="result"></p>
		</div>

		<div id="cardback">
		<h1>Get Started</h1>
		<h2>Choose your Passphrase</h2>
		<p>Your account is almost ready except for a passphrase.</p>
		<form method="POST" action="na.php" name="gplusxtra" onsubmit="return finishproc(this);">
		<div id="div-fname"><label style="width:30%;display:inline-block;" for="xfname">First name</label><input look="holo" style="width:60%" type="text" name="xfname" id="xfname" minlength=4 required disabled><br /></div>
		<div id="div-lname"><label style="width:30%;display:inline-block;" for="xlname">Last name</label><input look="holo" style="width:60%" type="text" name="xlname" id="xlname" required disabled><br /></div>
		<div id="div-email"><label style="width:30%;display:inline-block;" for="xemail">Email</label><input look="holo" style="width:60%" type="email" name="xemail" id="xemail" required disabled><br /></div>
		<div id="div-bday"><label style="width:30%;display:inline-block;" for="xbday">Birthday</label><input type="date" name="xbday" id="xbday" min="1970-01-01" max="<?php echo date('d-m-Y'); ?>" value="2000-01-01" required disabled /><br /></div>
		<div id="div-gender"><label style="width:30%;display:inline-block;" for="xgender">Gender</label><select look="holo" name="xgender" id="xgender" required disabled><option>Male</option><option>Female</option><option>Unspecified</option></select><br /></div>
		<div id="div-pass"><label style="width:30%;display:inline-block;" for="xpassword">Passphrase</label><input look="holo" style="width:60%" type="password" name="xpassword" id="xpassword" minlength=8 onchange="validatekey(this,document.getElementById('xcpassword'));" required disabled><br /></div>
		<div id="div-cpass"><label style="width:30%;display:inline-block;" for="xcpassword">Confirm passphrase</label><input look="holo" style="width:60%" type="password" name="xcpassword" id="xcpassword" onchange="validatekey(document.getElementById('xpassword'),this);" required disabled><br /></div>
		<input type="submit" value="" style="width: 100%"><br />
		</form>
		</div>
	</card>
	</space>
	<footer>
		<span>Handcrafted with love by the techies at <a>MIT</a> and our awesome contributers.</span>
		<a id="aghlogo" href="https://github.com/RyanAfrish7/pingR" target="_blank"><img class="logo" width="24px" src="/resources/GitHub.png" /></a>
	</footer>
</body>
</html>