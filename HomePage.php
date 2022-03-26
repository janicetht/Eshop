<style>
.banner-btn ul{
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow: hidden;
	width:100%;
}
.banner-btn li{
	display: inline;
}
</style>

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
				<li><a href="auth-process.php?action=logout">Logout</a></li>
			</ul>
		</nav>
	</div>
<section id="banner">
	<div class="banner-text">
		<h1>Janice Beauty Online Shop</h1>
		<p>Get you beauty products here!</p>
		<div class="banner-btn">
		<?php print $cat;?>
		</div>
	</div>
</section>

</body>
</html>
