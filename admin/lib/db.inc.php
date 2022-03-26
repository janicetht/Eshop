<?php
ini_set('display_errors',1);

function ierg4210_DB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/cart.db');

	// enable foreign key support
	$db->query('PRAGMA foreign_keys = ON;');

	// FETCH_ASSOC:
	// Specifies that the fetch method shall return each row as an
	// array indexed by column name as returned in the corresponding
	// result set. If the result set contains multiple columns with
	// the same name, PDO::FETCH_ASSOC returns only a single value
	// per column name.
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	return $db;
}

function ierg4210_cat_fetchall() {
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM CATEGORIES LIMIT 100;");
    if ($q->execute())
        return $q->fetchAll();
}

// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html
function ierg4210_prod_insert() {
    // input validation or sanitization

    // DB manipulation
    global $db;
    $db = ierg4210_DB();
	$catid = $_POST['catid'];
	$name = $_POST['name'];
	$price = $_POST['price'];
	$desc = $_POST['description'];
	$country = $_POST['country'];
	$inventory = $_POST['inventory'];
	$lastId = '';
	$return = '';
	
    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $catid))
        throw new Exception("invalid-catid");
    $catid = (int) $_POST['catid'];
    if (!preg_match('/^[\w\ ]+$/', $name))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $price))
        throw new Exception("invalid-price");
    //if (!preg_match('/^[\w\ ]+$/', $desc))
    //    throw new Exception("invalid-textdesc");
	if (!preg_match('/^[\w\- ]+$/', $country))
        throw new Exception("invalid-textcoun");
	if (!preg_match('/^[\d\]+$/', $inventory))
        throw new Exception("invalid-inventory");
	
    $sql="INSERT INTO PRODUCTS (CATID, NAME, PRICE, DESCRIPTION, COUNTRY, INVENTORY) VALUES (?, ?, ?, ?, ?, ?);";
    $q = $db->prepare($sql);

    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
        && $_FILES["file"]["size"] < 5000000) {

        $sql="INSERT INTO PRODUCTS (CATID, NAME, PRICE, DESCRIPTION, COUNTRY, INVENTORY) VALUES (?, ?, ?, ?, ?, ?);";
        $q = $db->prepare($sql);
        $q->bindParam(1, $catid, PDO::PARAM_INT);
        $q->bindParam(2, $name, PDO::PARAM_STR);
        $q->bindParam(3, $price, PDO::PARAM_STR);
        $q->bindParam(4, $desc, PDO::PARAM_STR);
		$q->bindParam(5, $country, PDO::PARAM_STR);
		$q->bindParam(6, $inventory, PDO::PARAM_INT);
        $q->execute();
        $lastId = $db->lastInsertId();
        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $lastId . ".jpg")) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin.php');
            exit();
        }
    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
	exit();
}

// TODO: add other functions here to make the whole application complete
function ierg4210_cat_insert() 
{
	global $db;
    $db = ierg4210_DB();
	$name = $_POST['name'];
	
    if (!preg_match('/^[\w\- ]+$/', $name))
        throw new Exception("invalid-name");

    $sql="INSERT INTO CATEGORIES (NAME) VALUES (?);";
    $q = $db->prepare($sql);
	$q->bindParam(1, $name, PDO::PARAM_STR);
	$q->execute();
	header('Location: admin.php');
    exit();
}
function ierg4210_cat_edit()
{
	global $db;
    $db = ierg4210_DB();
	$catid = $_POST['catid'];
	$name = $_POST['name'];
	
	if (!preg_match('/^\d*$/', $catid))
        throw new Exception("invalid-catid");
    if (!preg_match('/^[\w\ ]+$/', $name))
        throw new Exception("invalid-name");

    $sql="UPDATE CATEGORIES SET NAME = (?) WHERE CATID = (?);";
    $q = $db->prepare($sql);
	$q->bindParam(1, $name, PDO::PARAM_STR);
	$q->bindParam(2, $catid, PDO::PARAM_INT);
	$q->execute();
	header('Location: admin.php');
    exit();
}
function ierg4210_cat_delete()
{
	global $db;
    $db = ierg4210_DB();
	$catid = $_POST['catid'];
	
	if (!preg_match('/^\d*$/', $catid))
        throw new Exception("invalid-catid");
	
	$sql="DELETE FROM PRODUCTS WHERE CATID = (?);";
    $q = $db->prepare($sql);
	$q->bindParam(1, $catid, PDO::PARAM_INT);
	$q->execute();
	
    $sql="DELETE FROM CATEGORIES WHERE CATID = (?);";
    $q = $db->prepare($sql);
	$q->bindParam(1, $catid, PDO::PARAM_INT);
	$q->execute();
	
    header('Location: admin.php');
    exit();
}
function ierg4210_prod_delete_by_catid()
{
	global $db;
    $db = ierg4210_DB();
	$catid = $_POST['catid'];
	
	if (!preg_match('/^\d*$/', $catid))
        throw new Exception("invalid-catid");

    $sql="DELETE FROM PRODUCTS WHERE CATID = (?);";
    $q = $db->prepare($sql);
	$q->bindParam(1, $catid, PDO::PARAM_INT);
	$q->execute();
	header('Location: admin.php');
    exit();
}
function ierg4210_prod_fetchAll()
{
	global $db;
    $db = ierg4210_DB();

    $sql="SELECT * FROM PRODUCTS LIMIT 100;";
    $q = $db->prepare($sql);
    if ($q->execute())
        return $q->fetchAll();
}
function ierg4210_prod_fetchByPage($catid, $pageNum) 
{
	global $db;
	$db = ierg4210_DB();
  
	$itemPerPage = 6;
	
	$offset = $itemPerPage *  ($pageNum - 1);
	
	$sql="SELECT * FROM PRODUCTS WHERE CATID = (?) ORDER BY PID ASC LIMIT (?) OFFSET (?);";
	$q = $db->prepare($sql);
	$q->bindParam(1, $catid, PDO::PARAM_INT);
	$q->bindParam(2, $itemPerPage, PDO::PARAM_INT);
	$q->bindParam(3, $offset, PDO::PARAM_INT);
	if($q->execute())
		return $q->fetchAll();
}
function ierg4210_prod_edit()
{
	global $db;
    $db = ierg4210_DB();
	$pid = (int)$_POST['pid'];
	$catid = $_POST['catid'];
	$name = $_POST['name'];
	$price = $_POST['price'];
	$desc = $_POST['description'];
	$country = $_POST['country'];
	$inventory = $_POST['inventory'];
	
    // TODO: complete the rest of the INSERT command
	if (!preg_match('/^\d*$/', $pid))
        throw new Exception("invalid-pid");
    if (!preg_match('/^\d*$/', $catid))
        throw new Exception("invalid-catid");
    $catid = (int) $_POST['catid'];
    if (!preg_match('/^[\w\ ]+$/', $name))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $price))
        throw new Exception("invalid-price");
    //if (!preg_match('/^[\w\- ]+$/', $description))
    //    throw new Exception("invalid-textt");
	if (!preg_match('/^[\w\- ]+$/', $country))
        throw new Exception("invalid-textcoun");
	if (!preg_match('/^[\d\]+$/', $inventory))
        throw new Exception("invalid-inventory");

    $sql="UPDATE PRODUCTS SET CATID = (?), NAME = (?), PRICE = (?), DESCRIPTION = (?), COUNTRY = (?), INVENTORY = (?) WHERE PID = (?);";
    $q = $db->prepare($sql);

    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
        && $_FILES["file"]["size"] < 5000000){

        $sql="UPDATE PRODUCTS SET CATID = (?), NAME = (?), PRICE = (?), DESCRIPTION = (?), COUNTRY = (?), INVENTORY = (?) WHERE PID = (?);";
        $q = $db->prepare($sql);
		$q->bindParam(1, $catid, PDO::PARAM_INT);
		$q->bindParam(2, $name, PDO::PARAM_STR);
		$q->bindParam(3, $price, PDO::PARAM_STR);
		$q->bindParam(4, $desc, PDO::PARAM_STR);
		$q->bindParam(5, $country, PDO::PARAM_STR);
		$q->bindParam(6, $inventory, PDO::PARAM_INT);
		$q->bindParam(7, $pid, PDO::PARAM_INT);
        $q->execute();

        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $pid . ".jpg")) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin.php');
            exit();
        }
    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}
function ierg4210_prod_delete()
{
	global $db;
    $db = ierg4210_DB();
	$pid = $_POST['pid'];
	
	if (!preg_match('/^\d*$/', $pid))
        throw new Exception("invalid-pid");

    $sql="DELETE FROM PRODUCTS WHERE PID = (?);";
    $q = $db->prepare($sql);
	$q->bindParam(1, $pid, PDO::PARAM_INT);
	$q->execute();
	header('Location: admin.php');
    exit();
}
function ierg4210_add_user() 
{
	global $db;
    $db = ierg4210_DB();
	
	$email = $_POST['email'];
	$password = $_POST['password'];
	$admin_flag = $_POST['admin_flag'];
	
	//if (!preg_match('[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$', $email))
    //    throw new Exception("invalid-email");
	//if (!preg_match('(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}', $password))
    //    throw new Exception("invalid-password");
	
	$salt = random_int(PHP_INT_MIN ,PHP_INT_MAX);
	$hash_password = hash_hmac('sha256', $password, $salt);
	//$hash_default_salt = password_hash($password, PASSWORD_DEFAULT);
	
	$sql="INSERT INTO USERS (EMAIL, SALT, PASSWORD, ADMIN) VALUES (?,?,?,?);";
    $q = $db->prepare($sql);
	$q->bindParam(1, $email, PDO::PARAM_STR);
	$q->bindParam(2, $salt, PDO::PARAM_INT);
	$q->bindParam(3, $hash_password, PDO::PARAM_STR);
	$q->bindParam(4, $admin_flag, PDO::PARAM_INT);
	$q->execute();
	header('Content-Type: text/html; charset=utf-8');
    echo 'User Added. <br/><a href="javascript:history.back();">Go Back</a>';
	exit();
}
function ierg4210_user_login() 
{
	global $db;
    $db = ierg4210_DB();
	
	$login_success = 0;
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	if (!preg_match("/^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/", $email))
        throw new Exception("invalid-email");
	if (!preg_match("/^[\w@#$%^&*-]+$/", $password))
        throw new Exception("invalid-password");
	
    $sql="SELECT * FROM USERS WHERE EMAIL = ?;";
    $q = $db->prepare($sql);
	$q->bindParam(1, $email, PDO::PARAM_STR);
	$q->execute();
	$res = $q->fetch();
	$saltedPassword = hash_hmac('sha256', $password, $res['SALT']);
	
	if ($saltedPassword == $res['PASSWORD']) 
	{
		session_start();
		$exp = time() + 3600 * 24 * 3; 		// 3days
		$token = array(
		'em'=>$res['EMAIL'],
		'exp'=>$exp,
		'k'=>hash_hmac('sha256', $exp.$res['PASSWORD'], $res['SALT'])
		);
		setcookie('s4210', json_encode($token), $exp, '', '', true, true);
		$_SESSION['s4210'] = $token;
		//return true;
		$login_success = 1;
	}

	if ($login_success) {
		if ($res['ADMIN'] == 1) {
			header('Location: admin/admin.php', true, 302);
			exit();
		} else if ($res['ADMIN'] == 0) {
			header('Location: HomePage.php', true, 302);
			exit();
		}
	} else {
		throw new Exception('Wrong Credentials');
	}
}
function ierg4210_guest_login()
{
	session_start();
	$exp = time() + 3600 * 24 * 3; 		// 3days
	$token = array(
	'em'=>'guest',
	'exp'=>$exp,
	);
	setcookie('s4210', json_encode($token), $exp, '', '', true, true);
	$_SESSION['s4210'] = $token;
	header('Location: HomePage.php', true, 302);
	exit();
}
function ierg4210_logout()
{
	session_start();
	// clear the cookies and session
	setcookie('s4210', time() - 3600);
	unset($_SESSION['s4210']);
	// redirect to login page after logout
	header('Location: ../login.php', true, 302);
	exit();
}
