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
				<li><a href="#">Profile</a></li>
				<li><a href="auth-process.php?action=logout">Logout</a></li>
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
		<div class="product-detail-image">
			<p><img src="/admin/lib/images/<?php print $pid;?>.jpg"/></p>
		</div>
		
		<div class="product-detail-description">
			<p id="title"><?php print $name;?></p>
			<p id="detail-cart-btn-td"></p>
			</br>
			<p>Made in <?php print $country;?></p>
			</br>
			<p>HKD<?php print $price;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inventory:<?php print $inventory;?></p>
			</br>
			<div class="detail-cart-btn">
				<input type="button" name="add_to_cart" id="<?php print $pid;?>" class="btn btn-success form-control add_to_cart" value="Add to Cart" />
			</div>
			</br>
			<p id="how-to-use">How To Use</p>
			</br>
			<p><?php print $desc;?></p>
			<input type="hidden" name="hidden_quantity" id="quantity<?php print $pid;?>" class="form-control" value="1" />
			<input type="hidden" name="hidden_name" id="name<?php print $pid;?>" value="<?php print $name;?>" />
			<input type="hidden" name="hidden_price" id="price<?php print $pid;?>" value="<?php print $price;?>" />
		</div>		
    </div>

  </section>

</body>
</html>
<script src="cart.js"></script>