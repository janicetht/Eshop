<?php
require __DIR__.'/admin/lib/db.inc.php';
$res = ierg4210_cat_fetchall();

$cat = '<ul>';
$navCat = '';

foreach ($res as $value){
    $cat .= '<li><a href = "MainPage.php?CATID='.$value["CATID"].'"> '.$value["NAME"].'</a></li>';
	$navCat .= '<li><a href = "MainPage.php?CATID='.$value["CATID"].'"> '.$value["NAME"].'</a></li>';
}

$cat .= '</ul>';

session_start();
function csrf_getNonce($action)
{
	$nonce = mt_rand() . mt_rand();
	if (!isset($_SESSION['csrf_nonce']))
		$_SESSION['csrf_nonce'] = array();
	$_SESSION['csrf_nonce'][$action] = $nonce;
	return $nonce;
}

$em = $_SESSION['s4210']['em'];
?>

<html>
<head>
	<title>Janice Beauty Online Shop</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="topNavBar">
		<nav>
			<ul id="topNavBar">
				<li><a href="HomePage.php">Home</a></li>
				<?php print $navCat;?>
				<li><a href="#">Profile</a></li>
				<li><form id="logout_form"action="auth-process.php?action=<?php echo ($action = 'logout'); ?>" method="POST">
				<a href="javascript:;" onclick="document.getElementById('logout_form').submit();">Logout</a>
				<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/></form></li>
			</ul>
		</nav>
	</div>
<section id="banner">
	<div class="form-container">
	<div class="form-text">
    <form id="edit_password" method="POST" action="auth-process.php?action=<?php echo ($action = 'edit_password'); ?>" enctype="multipart/form-data">
        <label for="username">Email: <?php echo htmlspecialchars($em, ENT_COMPAT,'ISO-8859-1', true);?></label><br/><br/>
		<label for="pwd">Old Password *</label><br>
		<input type="password" id="old_password" name="old_password" pattern="^[\w@#$%^&*-]+$" ><br/>
		<label for="pwd">New Password *</label><br>
		<input type="password" id="new_password" name="new_password" pattern="^[\w@#$%^&*-]+$" ><br/><br/>
		<input type="submit" value="Submit" />
		<input type="hidden" name="email" value="<?php echo htmlspecialchars($em, ENT_COMPAT,'ISO-8859-1', true); ?>"/>
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
    </form>
	</div>
	</div>
</section>

</body>
</html>
