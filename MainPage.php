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
			<form action="payments.php" method="post" id="form1">
				<span class="shoppingListContent">
				<span id="cart_details"></span>
				<div id="cart_btn">
					<div id="paypal-button-container">
				    <div id="check_out_cart_btn">
					<a href="#" class="btn btn-primary" id="check_out_cart">
					<span class="glyphicon glyphicon-shopping-cart"></span> Check out
					</a>
					</div>
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
<script src="https://www.paypal.com/sdk/js?client-id=AXOWYIvAUgTpPw7ADHRgNH7mqZ0n7837moB8exERpHvR3iOXOw2HL16SHMfhAHpEgOaA3VbzB7ou6WdP&currency=HKD"></script>
<script>
	myFunction();
	function myFunction() {
		var em = document.getElementById("em").value;
		var x = document.getElementById("profileBtn");
		if (em == "guest") {
			x.style.display = "none";
		}
	}
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
</script>
<script src="cart.js"></script>
<script src="scroll.js"></script>