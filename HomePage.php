<style>
	.banner-btn ul {
		list-style-type: none;
		margin: 0;
		padding: 0;
		overflow: hidden;
		width: 100%;
	}

	.banner-btn li {
		display: inline;
	}
</style>

<?php
require __DIR__ . '/admin/lib/db.inc.php';
$res = ierg4210_cat_fetchall();

$cat = '<ul>';
$navCat = '';

foreach ($res as $value) {
	$cat .= '<li><a href = "MainPage.php?CATID=' . $value["CATID"] . '"> ' . $value["NAME"] . '</a></li>';
	$navCat .= '<li><a href = "MainPage.php?CATID=' . $value["CATID"] . '"> ' . $value["NAME"] . '</a></li>';
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
				<?php print $navCat; ?>
				<div id="profileBtn">
					<li><a href="profile.php">Profile</a></li>
				</div>
				<li>
					<form id="logout_form" action="auth-process.php?action=<?php echo ($action = 'logout'); ?>" method="POST">
						<a href="javascript:;" onclick="document.getElementById('logout_form').submit();">Logout</a>
						<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>" />
					</form>
				</li>
			</ul>
		</nav>
	</div>
	<section id="banner">
		<div class="banner-text">
			<h1>Janice Beauty Online Shop</h1>
			<p>Welcome, <?php echo htmlspecialchars($em, ENT_COMPAT,'ISO-8859-1', true); ?>!</p>
			<p>Get your beauty products here!</p>
			<div class="banner-btn">
				<?php print $cat; ?>
			</div>
		</div>
	</section>
	<input type="hidden" id="em" value="<?php echo htmlspecialchars($em, ENT_COMPAT,'ISO-8859-1', true); ?>"/>
</body>

</html>

<script>
	myFunction();
	function myFunction() {
		var em = document.getElementById("em").value;
		var x = document.getElementById("profileBtn");
		if (em == "guest") {
			x.style.display = "none";
		}
	}
</script>