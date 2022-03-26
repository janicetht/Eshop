<?php
session_start();
function csrf_getNonce($action)
{
	$nonce = mt_rand() . mt_rand();
	if (!isset($_SESSION['csrf_nonce']))
		$_SESSION['csrf_nonce'] = array();
	$_SESSION['csrf_nonce'][$action] = $nonce;
	return $nonce;
}
?>
<html>
<head>
	<title>Janice Beauty Online Shop</title>
	<script src="admin/js/jquery.min.js"></script>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="topNavBar">
		<nav>
		<ul id="topNavBar">
			</li><img id="banner-pic" src="/admin/lib/images/logo.png"/></li>
		</ul>
		</nav>
	</div>
	<section id="banner">
	<div class="login-container">
	<div class="login-text">
		<form method="POST" action="auth-process.php?action=<?php echo ($action = 'user_login'); ?>" enctype="multipart/form-data">
			<label for="username">Email:</label><br>
			<input type="email" id="email" name="email" pattern="^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$"><br>
			<label for="pwd">Password:</label><br>
			<input type="password" id="password" name="password" pattern="^[\w@#$%^&*-]+$" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"><br/><br/>
			<input type="submit" value="Submit">
			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
		</form>
		</br>
		<form method="POST" action="auth-process.php?action=<?php echo ($action = 'guest_login'); ?>">
			<label for="guest">No account? Sign in as guest!</label><br>
			<input type="submit" value="Guest">
			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
		</form>
	</div>
	</div>
</body>
</html>
