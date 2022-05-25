
<?php

//fetch_cart.php

session_start();

$total_price = 0;
$total_item = 0;
$item_num = 1;
$output = '
<div class="table-responsive" id="order_table">
	<table class="table table-bordered table-striped">
		<tr>  
            <th width="40%">Product Name</th>  
            <th width="10%">Quantity</th>  
            <th width="20%">Price</th>  
            <th width="15%">Total</th>  
            <th width="5%">Action</th>  
        </tr>
';
if(!empty($_SESSION["shopping_cart"]))
{
	foreach($_SESSION["shopping_cart"] as $keys => $values)
	{
		$output .= '
		<tr>
			<td>'.$values["name"].'</td>
			<td><input type="number" id="input_quantity'.$values["pid"].'" onchange="quantityChange('.$values["pid"].')" type="text" value="'.$values["quantity"].'"/></td>
			<td align="right">$ '.$values["price"].'</td>
			<td align="right">$ '.number_format($values["quantity"] * $values["price"], 2).'</td>
			<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'. $values["pid"].'">Remove</button></td>
			<input type="hidden" name="item_name_'.$item_num.'" value="'.$values["name"].'" />
			<input type="hidden" name="amount_'.$item_num.'" value="'.$values["price"].'" />
			<input type="hidden" name="quantity_'.$item_num.'" value="'.$values["quantity"].'" />
		</tr>
		';
		$total_price = $total_price + ($values["quantity"] * $values["price"]);
		$total_item = $total_item + 1;
		$item_num = $item_num + 1;
	}
	$output .= '
	<tr>  
        <td colspan="3" align="right">Total</td>  
        <td align="right">$ '.number_format($total_price, 2).'</td>  
        <td></td>  
    </tr>
	';
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
$output .= '</table></div>';
$output .= '<input type="hidden" name="cmd" value="_cart" />
<input type="hidden" name="upload" value="1" />
<input type="hidden" name="business" value="sb-u3u9b15570565@business.example.com" />
<input type="hidden" name="currency_code" value="HKD" />
<input type="hidden" name="charset" value="utf-8" />
<input type="hidden" name="total_item" value='.$total_item.' />
<input type="hidden" name="custom" value="0" />
<input type="hidden" name="invoice" value="0" />';
$data = array(
	'cart_details'		=>	$output,
	'total_price'		=>	'$' . number_format($total_price, 2),
	'total_item'		=>	$total_item
);	

echo json_encode($data);

?>