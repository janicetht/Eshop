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
				<li>
					<div class="cart">
						<img src="/admin/lib/images/cart.ico"/>
					</div>
				</li>
	  		<li><a href="HomePage.php"><img id="banner-pic" src="/admin/lib/images/logo.png"/></a></li>
				<li><img id=menuBtn src="/admin/lib/images/menu.png"></li>
			</ul>
		</nav>
	</div>
  <section id="banner">
    <div class="navBar">
		<br/>
        <a href="HomePage.php">Home</a><a> > Main Page</a>
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
    <br>
	<ul id="Product" class="table">
	</ul>

  </section>

</body>
</html>

<script src="cart.js"></script>
<script src="scroll.js"></script>