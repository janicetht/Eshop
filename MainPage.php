<?php
require __DIR__.'/admin/lib/db.inc.php';
$res = ierg4210_cat_fetchall();

$navCat = '';

foreach ($res as $value){
	$navCat .= '<li><a href = "MainPage.php?CATID='.$value["CATID"].'"> '.$value["NAME"].'</a></li>';
}
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
	<script src="admin/js/jquery.min.js"></script>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="topNavBar">
		<nav>
			<ul id="topNavBar">
				<li><a href="HomePage.php">Home</a></li>
				<?php print $navCat;?>
				<div id="profileBtn">
					<li><a href="profile.php">Profile</a></li>
				</div>
				<li><a href="cart.php">Cart</a></li>
				<li><form id="logout_form"action="auth-process.php?action=<?php echo ($action = 'logout'); ?>" method="POST">
				<a href="javascript:;" onclick="document.getElementById('logout_form').submit();">Logout</a>
				<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/></form></li>
			</ul>
		</nav>
	</div>
  <section id="banner">
    <div class="navBar">
		<br/>
        <a href="HomePage.php">Home</a><a> > Main Page</a>
			
			<div class="shopping-list">Shopping List
			<form action="javascript:void(0);" id="checkout-form" onsubmit="submitFormData(this)">
				<input type="hidden" id="em" name="em" value="<?php echo htmlspecialchars($em, ENT_COMPAT, 'ISO-8859-1', true); ?>" />
				<span class="shoppingListContent">
				<span id="cart_details"></span>
				<div id="cart_btn">
					<div id="check_out_cart_btn">
                        <input type="submit" class="btn btn-success" id="check_out_cart" form="checkout-form" value="Checkout">
                    </div>
					<div id="clear_cart_btn">
					<a href="#" class="btn btn-default" id="clear_cart">
					<span class="glyphicon glyphicon-trash"></span> Clear
					</a>
					</div>
				</div>
				</span>	
			</div>
			</form>
    </div>
    <br>
	<ul id="Product" class="table">
	</ul>
	<input type="hidden" id="em" value="<?php echo htmlspecialchars($em, ENT_COMPAT,'ISO-8859-1', true); ?>"/>
  </section>
  
</body>
</html>
<script src="payment.js"></script>
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
<script src="cart.js"></script>
<script src="scroll.js"></script>