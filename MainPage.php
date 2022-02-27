<?php
require __DIR__.'/admin/lib/db.inc.php';
$res = ierg4210_prod_fetchall();

$catid = (int)$_GET['CATID'];

$products = '';

foreach ($res as $value){
	if ((int)$value["CATID"] == $catid) { 
	$products .= '<li><a href="ProductPage.php?PID='.$value["PID"].'"><img src="/admin/lib/images/'.$value["PID"].'.jpg"/></a>
		<br><a href="ProductPage.php?PID='.$value["PID"].'">'.$value["NAME"].'</a><br>HKD'.$value["PRICE"].'<br>
		<div class="cart-btn"><a href="#">Add to Cart</a></div></li>';
	}
}

$products .= '</ul>';
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
				<li>
					<div class="cart">
						<img src="images/cart.ico"/>
					</div>
				</li>
	  		<li><a href="HomePage.php"><img id="banner-pic" src="images/logo.png"/></a></li>
				<li><img id=menuBtn src="images/menu.png"></li>
			</ul>
		</nav>
	</div>
  <section id="banner">
    <div class="navBar">
			<br/>
      <a href="HomePage.php">Home</a><a> > Main Page</a>
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
    <br>
    <ul id="Product" class="table">
    <?php print $products;?>

  </section>


</body>
</html>
