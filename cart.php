<?php
require __DIR__ . '/admin/lib/db.inc.php';
$res = ierg4210_cat_fetchall();

$navCat = '';

foreach ($res as $value) {
	$navCat .= '<li><a href = "MainPage.php?CATID=' . $value["CATID"] . '"> ' . $value["NAME"] . '</a></li>';
}

session_start();

$output = '';
$total_price = 0;
$total_item = 0;
$item_num = 1;

if(!empty($_SESSION["shopping_cart"]))
{
    foreach($_SESSION["shopping_cart"] as $keys => $values)
    {
        $output .= '
            <tr>
            <td class="col-sm-8 col-md-6">
            <div class="media">
                <a class="thumbnail pull-left" href="ProductPage.php?PID='.$values["pid"].'"> <img class="media-object" src="/admin/lib/images/'.$values["pid"].'.jpg" style="width: 52px; height: 52px;"> </a>
                <div class="media-body">
                    <h4 class="media-heading"><a href="ProductPage.php?PID='.$values["pid"].'">'.$values["name"].'</a></h4>
                </div>
            </div></td>
            <td class="col-sm-1 col-md-1" style="text-align: center"><input type="number" class="form-control" id="input_quantity'.$values["pid"].'" onchange="quantityChange1('.$values["pid"].')" type="text" value="'.$values["quantity"].'"/></td>
            <td class="col-sm-1 col-md-1 text-center"><strong>$ '.$values["price"].'</strong></td>
            <td class="col-sm-1 col-md-1 text-center"><strong>$ '.number_format($values["quantity"] * $values["price"], 2).'</strong></td>
            <td class="col-sm-1 col-md-1">
            <button type="button" name="delete" class="btn btn-danger delete" id="'. $values["pid"].'" onclick="remove('.$values["pid"].')">
                <span class="glyphicon glyphicon-remove"></span> Remove
            </button></td>
            <input type="hidden" name="item_name_'.$item_num.'" value="'.$values["name"].'" />
			<input type="hidden" name="amount_'.$item_num.'" value="'.$values["price"].'" />
			<input type="hidden" name="quantity_'.$item_num.'" value="'.$values["quantity"].'" />
            </tr>
        ';  
        $total_price = $total_price + ($values["quantity"] * $values["price"]);
		$total_item = $total_item + 1;
		$item_num = $item_num + 1;
    }
}
else
{
	$output .= '
    <tr>
    	<td colspan="5" align="center">
    		Your Cart is Empty!
    	</td>
    </tr>
    ';
}

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
<style>
    .container {
        padding-top: 70px;
    }
</style>

<html>
    <head>
        <title>Janice Beauty Online Shop</title>
	    <script src="admin/js/jquery.min.js"></script>
	    <link rel="stylesheet" href="style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
        
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

        <!--Shopping Cart-->
        <form action="payments.php" method="post" id="form1">
        <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Total</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php print $output;?>
                                <tr>
                                    <td>   </td>
                                    <td>   </td>
                                    <td>   </td>
                                    <td><h5>Total</h5></td>
                                    <td class="text-right"><h5><strong>$<?php print $total_price;?></strong></h5></td>
                                </tr>
                                <tr>
                                    <td>   </td>
                                    <td>   </td>
                                    <td>   </td>
                                    <td>
                                    <div id="clear_cart_btn">
				                	<button type="button" name="clear" class="btn btn-primary" id="clear_cart" onclick="clearrr()">
				                	<span class="glyphicon glyphicon-trash"></span> Clear
                                    </button>
                                    </td>
                                    <td>
                                    <input type="hidden" name="cmd" value="_cart" />
                                    <input type="hidden" name="upload" value="1" />
                                    <input type="hidden" name="business" value="sb-5dmam5523323@business.example.com" />
                                    <input type="hidden" name="currency_code" value="HKD" />
                                    <input type="hidden" name="charset" value="utf-8" />
                                    <input type="hidden" name="custom" value="0" />
                                    <input type="hidden" name="invoice" value="0" />
                                    
                					<div id="paypal-button-container"></div>
				                    <div id="check_out_cart_btn">
                                    <input type="submit" class="btn btn-success" id="check_out_cart" form="form1" value="Checkout">
				                	
				                	</div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </form>
            <input type="hidden" id="em" value="<?php echo htmlspecialchars($em, ENT_COMPAT,'ISO-8859-1', true); ?>"/>
    </body>
</html> 
<script src="https://www.paypal.com/sdk/js?client-id=AXOWYIvAUgTpPw7ADHRgNH7mqZ0n7837moB8exERpHvR3iOXOw2HL16SHMfhAHpEgOaA3VbzB7ou6WdP&currency=HKD"></script>
<script>
    console.log("Hello world!");
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

    function quantityChange1(pid) {
        var pid = pid;
        var quantity = $('#input_quantity' + pid).val();
        var action = "changeQuantity";
        if (quantity > 0) {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { pid: pid, quantity: quantity, action: action },
                success: function(data) {
                    location.reload();
                    //alert("Item has been Added into Cart");
                }
            });
        } else {
            alert("Quantity has to be >0");
        }
    }

    function remove(pid) {
        var pid = pid;
        var action = 'remove';
        if (confirm("Are you sure you want to remove this product?")) {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { pid: pid, action: action },
                success: function() {
                    location.reload();
                }
            })
        } else {
            return false;
        }
    }

    function clearrr(pid) {
        var action = 'empty';
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { action: action },
            success: function() {
                location.reload();
                alert("Your Cart has been clear");
            }
        })
    }

</script>
