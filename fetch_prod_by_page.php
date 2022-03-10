<?php
ini_set('display_errors', 1);

require __DIR__.'/admin/lib/db.inc.php';

$catid = $_POST['catid'];
$pageNum = $_POST['pageNum'];
	$itemPerPage = 6;
	
	$offset = $itemPerPage *  ($pageNum - 1);
$res = ierg4210_prod_fetchByPage($catid, $pageNum);

$products = '';

foreach ($res as $value){

	$products .= '<li><a href="ProductPage.php?PID='.$value["PID"].'"><img src="/admin/lib/images/'.$value["PID"].'.jpg"/></a>
		<br><a href="ProductPage.php?PID='.$value["PID"].'">'.$value["NAME"].'</a><br>HKD'.$value["PRICE"].'<br>
		<input type="hidden" name="hidden_quantity" id="quantity' . $value["PID"] .'" class="form-control" value="1" />
		<input type="button" name="add_to_cart" id="'.$value["PID"].'" style="margin-top:5px;" class="btn btn-success form-control add_to_cart" value="Add to Cart" />
		<input type="hidden" name="hidden_name" id="name'.$value["PID"].'" value="'.$value["NAME"].'" />
		<input type="hidden" name="hidden_price" id="price'.$value["PID"].'" value="'.$value["PRICE"].'" /></li>';

}

$data = array(
	'Product'	=>	$products
);

echo json_encode($data);
?>
