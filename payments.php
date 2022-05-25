<?php
ini_set('display_errors', 1);
require('functions.php');

// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

// Database settings. Change these for your database configuration.
/*$dbConfig = [
    'host' => 'localhost',
    'username' => 'user',
    'password' => 'secret',
    'name' => 'example_database'
];*/
global $db;
$db = ierg4210_DB();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
    'email' => 'sb-u3u9b15570565@business.example.com',
    'return_url' => 'https://secure.s48.ierg4210.ie.cuhk.edu.hk/payment-success.php',
    'cancel_url' => 'https://secure.s48.ierg4210.ie.cuhk.edu.hk/payment-cancelled.php',
    'notify_url' => 'https://secure.s48.ierg4210.ie.cuhk.edu.hk/payments.php'
];

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {
    $stmt = $db->prepare('INSERT INTO LOG (ERROR, TIME) VALUES (?,?)');
    $text = "if";
    $stmt->bindParam(1, $text);
    $stmt->bindParam(2, $date);
    $stmt->execute();
    // Grab the post data so that we can set up the query string for PayPal.
    // Ideally we'd use a whitelist here to check nothing is being injected into
    // our post data.
    $data = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = stripslashes($value);
    }

    // Set the PayPal account.
    //$data['business'] = $paypalConfig['email'];

    // Set the PayPal return addresses.
    $data['return'] = stripslashes($paypalConfig['return_url']);
    $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
    $data['notify_url'] = stripslashes($paypalConfig['notify_url']);

    // Set the details about the product being purchased, including the amount and currency so that these aren't overridden by the form data.
    //$data['item_name'] = $itemName;
    //$data['amount'] = $itemAmount;
    //$data['currency_code'] = 'GBP';

    // Add any custom fields for the query string.
    //$data['custom'] = USERID;

    // Build the query string from the data.
    $queryString = http_build_query($data);

    // Redirect to paypal IPN

    header('location:' . $paypalUrl . '?' . $queryString);
    exit();

} else {
    // Handle the PayPal response.

    // Create a connection to the database.
    //$db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['name']);

    // Assign posted variables to local data array.

    $data = [
        'payment_status' => $_POST['payment_status'],
        'payment_amount' => $_POST['mc_gross'],
        'payment_currency' => $_POST['mc_currency'],
        'txn_id' => $_POST['txn_id'],
        'receiver_email' => $_POST['receiver_email'],
        'custom' => $_POST['custom'],
        'invoice' => $_POST['invoice']
    ];
    for ($i = 1; $i < (int)$_POST["total_item"] + 1; $i++) {
        $data["item_name_" . $i] = $_POST["item_name_" . $i];
        $data["quantity_" . $i] = $_POST["quantity_" . $i];
    }

    // We need to verify the transaction comes from PayPal and check we've not
    // already processed the transaction before adding the payment to our
    // database.
    $flag1 = verifyTransaction($_POST);
    $flag2 = checkTxnid($db, $data['txn_id']);

    if ($flag1 && $flag2) {
        if (addPayment($db, $data) !== false) {
            // Payment successfully added into db.
        }
    } else {
        //Payment failed
        if (failPayment($db, $data) !== false) {
            // Payment successfully set to fail into db.
        }
    }
}
