<?php
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
	if (!preg_match('/^[\d\.]+$/', $inventory))
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
        $q->bindParam(1, $catid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $price);
        $q->bindParam(4, $desc);
		$q->bindParam(5, $country);
		$q->bindParam(6, $inventory);
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

    $sql="INSERT INTO CATEGORIES (NAME) VALUES ('$name');";
    $q = $db->prepare($sql);
	$q->execute();
	header('Content-Type: text/html; charset=utf-8');
    echo 'Inserted. <br/><a href="../admin.php">Back to admin panel.</a>';
    exit();
}
function ierg4210_cat_edit()
{
	global $db;
    $db = ierg4210_DB();
	$catid = $_POST['catid'];
	$name = $_POST['name'];
	
    if (!preg_match('/^[\w\- ]+$/', $name))
        throw new Exception("invalid-name");

    $sql="UPDATE CATEGORIES SET NAME = ('$name') WHERE CATID = ('$catid');";
    $q = $db->prepare($sql);
	$q->execute();
    exit();
}
function ierg4210_cat_delete()
{
	global $db;
    $db = ierg4210_DB();
	$catid = $_POST['catid'];
	
    $sql="DELETE FROM CATEGORIES WHERE CATID = ('$catid');";
    $q = $db->prepare($sql);
	$q->execute();
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Location: admin.php');
    exit();
}
function ierg4210_prod_delete_by_catid()
{
	global $db;
    $db = ierg4210_DB();
	$catid = $_POST['catid'];

    $sql="DELETE FROM PRODUCTS WHERE CATID = ('$catid');";
    $q = $db->prepare($sql);
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
function ierg4210_prod_fetchOne()
{
	global $db;
    $db = ierg4210_DB();
	$pid = $_POST['pid'];
	#header('Content-Type: text/html; charset=utf-8');
    #echo ''$pid'. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
	$sql="SELECT * FROM PRODUCTS WHERE PID = ('$pid');";
    $q = $db->prepare($sql);

    if ($q->execute())
        return $q->fetchAll();
}
/*function ierg4210_prod_edit()
{
	global $db;
    $db = ierg4210_DB();
	$pid = $_POST['pid'];
	$catid = $_POST['catid'];
	$name = $_POST['name'];
	$price = $_POST['price'];
	$desc = $_POST['description'];
	$country = $_POST['country'];
	$inventory = $_POST['inventory'];
	
    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $catid))
        throw new Exception("invalid-catid");
    $catid = (int) $_POST['catid'];
    if (!preg_match('/^[\w\- ]+$/', $name))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $price))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\w\- ]+$/', $description))
        throw new Exception("invalid-textt");

    $sql="UPDATE PRODUCTS SET CATID = ('$catid'), NAME = ('$name'), PRICE = ('$price'), DESCRIPTION = ('$desc'), COUNTRY = ('$country'), INVENTORY = ('$inventory') WHERE PID = ('$pid');";
    $q = $db->prepare($sql);

    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
        && $_FILES["file"]["size"] < 5000000) {

        $sql="UPDATE PRODUCTS SET CATID = ('$catid'), NAME = ('$name'), PRICE = ('$price'), DESCRIPTION = ('$desc'), COUNTRY = ('$country'), INVENTORY = ('$inventory')WHERE PID = ('$pid');";
        $q = $db->prepare($sql);
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
}*/
function ierg4210_prod_delete()
{
	global $db;
    $db = ierg4210_DB();
	$pid = $_POST['pid'];

    $sql="DELETE FROM PRODUCTS WHERE PID = ('$pid');";
    $q = $db->prepare($sql);
	$q->execute();
	header('Location: admin.php');
    exit();
}
