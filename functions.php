<?php
ini_set('display_errors', 1);
require __DIR__ . '/admin/lib/db.inc.php';

function checkTxnid($db, $txnid)
{
    $stmt = $db->prepare('SELECT * FROM ORDERS WHERE txnid = (?);');
    $stmt->bindParam(1, $txnid);
    $stmt->execute();

    return !$stmt->rowCount();
}

function addPayment($db, $data)
{
    //TO BE IMPLEMENTED - adding payment record into db
    //Sample code from the reference

    $data['payment_status'] = 'success';

    if (is_array($data)) {
        $date = date('Y-m-d H:i:s');
        $stmt = $db->prepare('UPDATE ORDERS SET txnid = (?), PAYMENT_STATUS = (?), CREATEDATETIME = (?) WHERE INVOICE = (?);');
        $stmt->bindParam(1, $data['txn_id']);
        $stmt->bindParam(2, $data['payment_status']);
        $stmt->bindParam(3, $date);
        $stmt->bindParam(4, $data['invoice']);
        $stmt->execute();
        return 1;//$db->lastInsertId;
    }

    return false;
}

function failPayment($db, $data)
{
    //TO BE IMPLEMENTED - adding payment record into db
    //Sample code from the reference

    $data['payment_status'] = 'fail';

    if (is_array($data)) {
        $date = date('Y-m-d H:i:s');
        $stmt = $db->prepare('UPDATE ORDERS SET txnid = (?), PAYMENT_STATUS = (?), CREATEDATETIME = (?) WHERE INVOICE = (?);');
        $stmt->bindParam(1, $data['txn_id']);
        $stmt->bindParam(2, $data['payment_status']);
        $stmt->bindParam(3, $date);
        $stmt->bindParam(4, $data['invoice']);
        $stmt->execute();
        return 1;//$db->lastInsertId;
    }

    return false;
}

function verifyTransaction($data)
{
    global $paypalUrl;

    $req = 'cmd=_notify-validate';
    foreach ($data as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
        $req .= "&$key=$value";
    }

    $ch = curl_init($paypalUrl);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    $res = curl_exec($ch);

    if (!$res) {
        $errno = curl_errno($ch);
        $errstr = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error: [$errno] $errstr");
    }

    $info = curl_getinfo($ch);

    // Check the http response
    $httpCode = $info['http_code'];
    if ($httpCode != 200) {
        throw new Exception("PayPal responded with http code $httpCode");
    }

    curl_close($ch);

    return $res === 'VERIFIED';
}
