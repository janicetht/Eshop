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
				<li><div class="cart"><img src="/admin/lib/images/cart.ico"/></div></li>
	  		<li><a href="HomePage.php"><img id="banner-pic" src="/admin/lib/images/logo.png"/></a></li>
				<li><img id=menuBtn src="/admin/lib/images/menu.png"></li>
			</ul>
		</nav>
	</div>
  <section id="banner">
		<div class="navBar">
			<br/>
      <a href="HomePage.php">Home</a><a> > </a>
			<a href="MainPage.php?CATID=<?php print $catid;?>">Main Page</a><a> > Product Detail</a>
			<div class="shopping-list">Shopping List
				<span class="shoppingListContent">
				<span id="cart_details"></span>
				<div id="cart_btn">
				    <div id="check_out_cart_btn">
					<a href="#" class="btn btn-primary" id="check_out_cart">
					<span class="glyphicon glyphicon-shopping-cart"></span> Check out
					</a>
					</div>
					<div id="clear_cart_btn">
					<a href="#" class="btn btn-default" id="clear_cart">
					<span class="glyphicon glyphicon-trash"></span> Clear
					</a>
					</div>
				</div>
				</span>
				
			</div>
    </div>
    <div class="product-detail">
		<div class="product-detail-child">
        <p id="title"><?php print $name;?></p>
        <p><img src="/admin/lib/images/<?php print $pid;?>.jpg"/><div class="detail-cart-btn">
        <a href="#">Add to Cart</a>
		</div></p>
        <p id="detail-cart-btn-td"></p>
        <p>Made in <?php print $country;?></p>
        <p>HKD<?php print $price;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inventory:<?php print $inventory;?></p>
        <p id="how-to-use">How To Use</p>
        <p><?php print $desc;?></p>
		</div>
    </div>

  </section>


</body>
</html>
<script src="cart.js"></script>