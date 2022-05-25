<?php
require __DIR__.'/admin/lib/db.inc.php';
$pid = (int)$_GET['PID'];
$prodRes = ierg4210_prod_fetchAll();
$prodOptions = '';
$catid = '';
$name = '';
$price = '';
$desc = '';
$country = '';
$inventory = '';

foreach ($prodRes as $value){
	if ((int)$value['PID'] == $pid) {
		$catid = $value['CATID'];
		$name = $value['NAME'];
		$price = $value['PRICE'];
		$desc = $value['DESCRIPTION'];
		$country = $value['COUNTRY'];
		$inventory = $value['INVENTORY'];
	}
}

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
      <a href="HomePage.php">Home</a><a> > </a>
			<a href="MainPage.php?CATID=<?php print urlencode($catid);?>">Main Page</a><a> > Product Detail</a>
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
    <div class="product-detail">
		<div class="product-detail-image">
			<p><img src="/admin/lib/images/<?php print htmlspecialchars($pid, ENT_COMPAT,'ISO-8859-1', true);?>.jpg"/></p>
		</div>
		
		<div class="product-detail-description">
			<p id="title"><?php print htmlspecialchars($name, ENT_COMPAT,'ISO-8859-1', true);?></p>
			<p id="detail-cart-btn-td"></p>
			</br>
			<p>Made in <?php print htmlspecialchars($country, ENT_COMPAT,'ISO-8859-1', true);?></p>
			</br>
			<p>HKD<?php print htmlspecialchars($price, ENT_COMPAT,'ISO-8859-1', true);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inventory:<?php print htmlspecialchars($inventory, ENT_COMPAT,'ISO-8859-1', true);?></p>
			</br>
			<div class="detail-cart-btn">
				<input type="button" name="add_to_cart" id="<?php print htmlspecialchars($pid, ENT_COMPAT,'ISO-8859-1', true);?>" class="btn btn-success form-control add_to_cart" value="Add to Cart" />
			</div>
			</br>
			<p id="how-to-use">How To Use</p>
			</br>
			<p><?php print htmlspecialchars($desc, ENT_COMPAT,'ISO-8859-1', true);?></p>
			<input type="hidden" name="hidden_quantity" id="quantity<?php print htmlspecialchars($pid, ENT_COMPAT,'ISO-8859-1', true);?>" class="form-control" value="1" />
			<input type="hidden" name="hidden_name" id="name<?php print htmlspecialchars($pid, ENT_COMPAT,'ISO-8859-1', true);?>" value="<?php print htmlspecialchars($name, ENT_COMPAT,'ISO-8859-1', true);?>" />
			<input type="hidden" name="hidden_price" id="price<?php print htmlspecialchars($pid, ENT_COMPAT,'ISO-8859-1', true);?>" value="<?php print htmlspecialchars($price, ENT_COMPAT,'ISO-8859-1', true);?>" />
		</div>		
    </div>
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
	console.log("log");
	paypal.Buttons({
        // Sets up the transaction when a payment button is clicked
        createOrder: (data, actions) => {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: '77.44' // Can also reference a variable or function
              }
            }]
          });
        },
        // Finalize the transaction after payer approval
        onApprove: (data, actions) => {
          return actions.order.capture().then(function(orderData) {
            // Successful capture! For dev/demo purposes:
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            const transaction = orderData.purchase_units[0].payments.captures[0];
            alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
            // When ready to go live, remove the alert and show a success message within this page. For example:
            // const element = document.getElementById('paypal-button-container');
            // element.innerHTML = '<h3>Thank you for your payment!</h3>';
            // Or go to another URL:  actions.redirect('thank_you.html');
          });
        }
    }).render('#paypal-button-container');
	console.log("log2");
</script>
<script src="cart.js"></script>