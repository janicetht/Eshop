<?php
require __DIR__.'/admin/lib/db.inc.php';
$res = ierg4210_prod_fetchall();

$catid = (int)$_GET['CATID'];

$products = '';

foreach ($res as $value){
	if ((int)$value["CATID"] == $catid) { 
	$products .= '<li><a href="ProductPage.php?PID='.$value["PID"].'"><img src="/admin/lib/images/'.$value["PID"].'.jpg"/></a>
		<br><a href="ProductPage.php?PID='.$value["PID"].'">'.$value["NAME"].'</a><br>HKD'.$value["PRICE"].'<br>
		<input type="hidden" name="hidden_quantity" id="quantity' . $value["PID"] .'" class="form-control" value="1" />
		<input type="button" name="add_to_cart" id="'.$value["PID"].'" style="margin-top:5px;" class="btn btn-success form-control add_to_cart" value="Add to Cart" />
		<input type="hidden" name="hidden_name" id="name'.$value["PID"].'" value="'.$value["NAME"].'" />
		<input type="hidden" name="hidden_price" id="price'.$value["PID"].'" value="'.$value["PRICE"].'" /></li>';
	}
}
$products .= '</ul>';
?>

<html>
<head>
	<title>Janice Beauty Online Shop</title>
	<script src="admin/js/jquery.min.js"></script>
	<link rel="stylesheet" href="style.css">
	<script src="admin/js/bootstrap.min.js"></script>
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
    <?php print $products;?>

  </section>

</body>
</html>

<script>  
$(document).ready(function(){

	load_cart_data();
    
	function load_cart_data()
	{
		$.ajax({
			url:"fetch_cart.php",
			method:"POST",
			dataType:"json",
			success:function(data)
			{
				$('#cart_details').html(data.cart_details);
				//$('.total_price').text(data.total_price);
				//$('.badge').text(data.total_item);
			}
		});
	}

	$(document).on('click', '.add_to_cart', function(){
		var pid = $(this).attr("id");
		var name = $('#name'+pid+'').val();
		var price = $('#price'+pid+'').val();
		var quantity = $('#quantity'+pid).val();
		var action = "add";
		if(quantity > 0)
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{pid:pid, name:name, price:price, quantity:quantity, action:action},
				success:function(data)
				{
					load_cart_data();
					alert("Item has been Added into Cart");
				}
			});
		}

	});

	$(document).on('click', '.delete', function(){
		var pid = $(this).attr("id");
		var action = 'remove';
		if(confirm("Are you sure you want to remove this product?"))
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{pid:pid, action:action},
				success:function()
				{
					load_cart_data();
					//$('#cart-popover').popover('hide');
					alert("Item has been removed from Cart");
				}
			})
		}
		else
		{
			return false;
		}
	});

	$(document).on('click', '#clear_cart', function(){
		var action = 'empty';
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:action},
			success:function()
			{
				load_cart_data();
				//$('#cart-popover').popover('hide');
				alert("Your Cart has been clear");
			}
		});
	});
    
});

</script>