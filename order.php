<?php
ini_set('display_errors',1);
require __DIR__ . '/admin/lib/db.inc.php';

function lastInsertId()
{
    global $db;
    $db = ierg4210_DB();
    return $db->lastInsertId();
}

function ierg4210_prod_search($item_name) {
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $sql = "SELECT PRICE FROM PRODUCTS WHERE NAME = (?);";
    $q = $db->prepare($sql);
	$q->bindParam(1, $item_name, PDO::PARAM_STR);
    if ($q->execute())
        return $q->fetchAll();
}

function getItemPrice($item_name)
{
    $result = ierg4210_prod_search($item_name);

    return (float)$result[0]['PRICE'];
}

//if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = [];
    $salt = bin2hex(random_bytes(32));
    $total_price = 0;
    /* Generate a inovice; MUST be unique to avoid being blocked. */
    $invoice = sprintf("%016d", lastInsertId()) . substr(bin2hex(random_bytes(32)), 0, 16);

    /* Genreate a digest */
    array_push(
        $data,
        $_POST["currency_code"],
        $_POST["business"],
        $salt
    );
    $itemList = [];
    for ($i = 1; $i < (int)$_POST["total_item"] + 1; $i++) {
        $itemPrice = getItemPrice($_POST["item_name_".$i]);
        array_push(
            $data, 
            $_POST["item_name_".$i],
            (int)$_POST["quantity_".$i],
            $itemPrice
        );
        array_push(
            $itemList,
            [
                'name' => $_POST["item_name_".$i],
                'quantity'=> $_POST["quantity_".$i]
            ]
            );
        $total_price = $total_price + ((int) $_POST["quantity_".$i] *$itemPrice);
    }
    array_push(
        $data,
        $total_price
    );

    $digest = hash("sha256", implode(";", $data));
    $em = $_POST["em"];

    /* Save $data, $invoice, $digest to DB */
    global $db;
    try {
        $db = ierg4210_DB();
        
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $itemJson = json_encode($itemList);

        $q = $db->prepare('INSERT INTO ORDERS (USER,DIGEST,PRODUCTS,SALT,INVOICE,PAYMENT_AMOUNT) VALUES (?,?,?,?,?,?)');
        $q->bindParam(1, $em);
        $q->bindParam(2, $digest);
        $q->bindParam(3, $itemJson);
        $q->bindParam(4, $salt);
        $q->bindParam(5, $invoice);
        $q->bindParam(6, $total_price);
        $q->execute();
    } catch (PDOException $e) {
        echo 'error '. $e->getMessage();
    }

    /* Reply for the POST */
    echo json_encode(array(
        "invoice" => $invoice,
        "digest" => $digest
    ));

//}
