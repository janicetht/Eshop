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
		#$prodOptions .= '<option value="'.$value['PID'].'"> '.$value['NAME'].' </option>';
}
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
				<li><div class="cart"><img src="images/cart.ico"/></div></li>
	  		<li><a href="HomePage.php"><img id="banner-pic" src="images/logo.png"/></a></li>
				<li><img id=menuBtn src="images/menu.png"></li>
			</ul>
		</nav>
	</div>
  <section id="banner">
		<div class="navBar">
			<br/>
      <a href="HomePage.php">Home</a><a> > </a>
			<a href="MainPage.php?CATID=<?php print $catid;?>">Main Page</a><a> > Product Detail</a>
			<div class="shopping-list">Shopping List
				<span class="shopping-list-content">
					<ul class="shopping-list-table">
					<li>Cushion Foundation<br/>Qty:<input type="text" value="1"/></li>
					<li>Lipstick<br/>Qty:<input type="text" value="2"/></li>
					<li><a href="#">Check Out</a></li>
					</ul>
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
