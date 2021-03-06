<?php
include_once('auth.php');
include_once('lib/db.inc.php');

if(($return = call_user_func('auth')) === false)
{
	header('Location: login.php', true, 302);
	exit();
}

header('Content-Type: application/json');

// input validation
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
	echo json_encode(array('failed'=>'undefined'));
	exit();
}

session_start();
function csrf_verifyNonce($action, $receivedNonce)
{
	if (isset($receivedNonce) && $_SESSION['csrf_nonce'][$action] == $receivedNonce) 
	{
		//if ($_SESSION['authtoken'] == null)
			unset($_SESSION['csrf_nonce'][$action]);
		return true;
	}
	throw new Exception('csrf-attack');	
}
csrf_verifyNonce($_REQUEST['action'], $_POST['nonce']);


// The following calls the appropriate function based to the request parameter $_REQUEST['action'],
//   (e.g. When $_REQUEST['action'] is 'cat_insert', the function ierg4210_cat_insert() is called)
// the return values of the functions are then encoded in JSON format and used as output
try {

	if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
		if ($db && $db->errorCode()) 
			error_log(print_r($db->errorInfo(), true));
		echo json_encode(array('failed'=>'1'));
	}
	echo 'while(1);' . json_encode(array('success' => $returnVal));
} catch(PDOException $e) {
	error_log($e->getMessage());
	echo json_encode(array('failed'=>'error-db'));
} catch(Exception $e) {
	echo 'while(1);' . json_encode(array('failed' => $e->getMessage()));
}
?>