<?php
//ini_set('display_errors',1);
require __DIR__ . '/lib/db.inc.php';

include_once('auth.php');

if(($return = call_user_func('auth')) === false)
{
	header('Location: ../login.php', true, 302);
	exit();
}

$catRes = ierg4210_cat_fetchall();
$prodRes = ierg4210_prod_fetchall();
$orderRes = ierg4210_orders_fetchall();

$catOptions = '';
$prodOptions = '';
$adminOptions = '';

foreach ($catRes as $value) {
    $catOptions .= '<option value="' . $value['CATID'] . '"> ' . $value['NAME'] . ' </option>';
}
foreach ($prodRes as $value) {
    $prodOptions .= '<option value="' . $value['PID'] . '"> ' . $value['NAME'] . ' </option>';
}

$adminOptions .= '<option value="1">Admin</option><option value="0">Non-admin</option>';

//session_start();
function csrf_getNonce($action)
{
	$nonce = mt_rand() . mt_rand();
	if (!isset($_SESSION['csrf_nonce']))
		$_SESSION['csrf_nonce'] = array();
	$_SESSION['csrf_nonce'][$action] = $nonce;
	return $nonce;
}
?>

<html>
<header>
	<title>Admin Page</title>
	<link rel="stylesheet" href="admin_style.css">
</header>
<body>
    <h3> Admin Page </h3>
	<div class="logout_button">
		<form method="POST" action="admin-process.php?action=<?php echo ($action = 'logout'); ?>">
			<input type="submit" value="Logout">
			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
		</form>
	</div>
<fieldset>
    <legend> Category</legend>
    <table>
        <tr>
            <th>Category ID </th>
            <th>Name</th>
        <tr>
            <?php
            foreach ($catRes as $value) {
                print '<tr>';
                print "<td>" . $value['CATID'] . "</td>";
                print "<td>" . $value['NAME'] . "</td>";
                print "</tr>";
            }
            ?>
        </tr>
    </table>
</fieldset>
</br>

<fieldset>
    <legend> Product</legend>
    <table>
        <tr>
            <th>Product ID </th>
            <th>Category ID </th>
            <th>Name </th>
            <th>Price </th>
            <th>Description</th>
            <th>Country of Origin</th>
            <th>Inventory</th>
            <th>Pic</th>
        <tr>
            <?php
            foreach ($prodRes as $value) {
                print '<tr>';
                print "<td>" . $value['PID'] . "</td>";
                print "<td>" . $value['CATID'] . "</td>";
                print "<td>" . $value['NAME'] . "</td>";
                print "<td>" . $value['PRICE'] . "</td>";
                print "<td>" . $value['DESCRIPTION'] . "</td>";
                print "<td>" . $value['COUNTRY'] . "</td>";
                print "<td>" . $value['INVENTORY'] . "</td>";
                print "<td><img src='/admin/lib/images/" . $value['PID'] . ".jpg'/></td>";
                print "</tr>";
            }
            ?>
        </tr>
    </table>
</fieldset>
</br>

<fieldset>
    <legend> Orders</legend>
    <table>
        <tr>
            <th>ID </th>
            <th>User </th>
            <th>Payment Status </th>
            <th>Payment Amount </th>
            <th>Products </th>
            <th>Create Datetime </th>
        <tr>
            <?php
            foreach ($orderRes as $value) {
                print '<tr>';
                print "<td>" . $value['ID'] . "</td>";
                print "<td>" . $value['USER'] . "</td>";
                print "<td>" . $value['PAYMENT_STATUS'] . "</td>";
                print "<td>" . $value['PAYMENT_AMOUNT'] . "</td>";
                print "<td>" . $value['PRODUCTS'] . "</td>";
                print "<td>" . $value['CREATEDATETIME'] . "</td>";
                print "</tr>";
            }
            ?>
        </tr>
    </table>
</fieldset>
</br>

<fieldset>
    <legend> Add Product</legend>
    <form id="prod_insert" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_insert'); ?>" enctype="multipart/form-data">
        <label for="prod_catid"> Category *</label>
        <div> <select id="prod_catid" name="catid"><?php echo $catOptions; ?></select></div>
        <label for="prod_name"> Name *</label>
        <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\]+$" /></div>
        <label for="prod_price"> Price *</label>
        <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$" /></div>
        <label for="prod_desc"> Description *</label>
        <div> <textarea id="prod_desc" type="text" name="description" required="required" rows="4" cols="40" pattern="^[\w\.\?\!\,\(\)\+\-\* ]+$"> </textarea> </div>
        <label for="prod_desc"> Country of Origin *</label>
        <div> <input id="prod_country" type="text" name="country" required="required" pattern="^[\w\]+$"/> </div>
        <label for="prod_desc"> Inventory *</label>
        <div> <input id="prod_inventory" type="number" name="inventory" required="required"/> </div>
        <label for="prod_image"> Image * </label>

        <div class="edrop-zone">
            <span class="edrop-zone__prompt">Drop file here or click to upload</span>
            <input class="edrop-zone__input" type="file" name="file" required="true" accept="image/jpeg" />
        </div>
        <script>
            document.querySelectorAll(".edrop-zone__input").forEach((inputElement) => {
                const dropZoneElement = inputElement.closest(".edrop-zone");

                dropZoneElement.addEventListener("click", (e) => {
                    inputElement.click();
                });

                inputElement.addEventListener("change", (e) => {
                    if (inputElement.files.length) {
                        updateThumbnail(dropZoneElement, inputElement.files[0]);
                    }
                });

                dropZoneElement.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    dropZoneElement.classList.add("edrop-zone--over");
                });

                ["dragleave", "dragend"].forEach((type) => {
                    dropZoneElement.addEventListener(type, (e) => {
                        dropZoneElement.classList.remove("edrop-zone--over");
                    });
                });

                dropZoneElement.addEventListener("drop", (e) => {
                    e.preventDefault();

                    if (e.dataTransfer.files.length) {
                        inputElement.files = e.dataTransfer.files;
                        updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
                    }

                    dropZoneElement.classList.remove("edrop-zone--over");
                });
            });

            function updateThumbnail(dropZoneElement, file) {
                let thumbnailElement = dropZoneElement.querySelector(".edrop-zone__thumb");
				
				if (file.type.startsWith("image/")) {
                
					if (dropZoneElement.querySelector(".edrop-zone__prompt")) {
						dropZoneElement.querySelector(".edrop-zone__prompt").remove();
					}

					if (!thumbnailElement) {
						thumbnailElement = document.createElement("div");
						thumbnailElement.classList.add("edrop-zone__thumb");
						dropZoneElement.appendChild(thumbnailElement);
					}

					thumbnailElement.dataset.label = file.name;
                
					const reader = new FileReader();

					reader.readAsDataURL(file);
					reader.onload = () => {
						thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
                    };
                } else {
                    thumbnailElement.style.backgroundImage = null;
                }
            }
        </script>

        </br>
        <input type="submit" value="Submit" />
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
    </form>
</fieldset>
</br>
<fieldset>
    <legend> Edit Product</legend>
    <form id="prod_edit" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_edit'); ?>" enctype="multipart/form-data">
        <label for="prod_catid"> Product *</label>
        <div> <select id="pid" name="pid"><?php echo $prodOptions; ?></select></div>
        <label for="prod_catid"> New Category *</label>
        <div> <select id="prod_catid" name="catid"><?php echo $catOptions; ?></select></div>
        <label for="prod_name"> New Name *</label>
        <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\]+$" /></div>
        <label for="prod_price"> New Price *</label>
        <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$"/></div>
        <label for="prod_desc"> New Description *</label>
        <div> <textarea id="prod_desc" type="text" name="description" rows="4" cols="40" pattern="^[\w\.\?\!\,\(\)\+\-\* ]+$"> </textarea> </div>
        <label for="prod_desc"> New Country of Origin *</label>
        <div> <input id="prod_country" type="text" name="country" required="required" pattern="^[\w\]+$"/> </div>
        <label for="prod_desc"> New Inventory *</label>
        <div> <input id="prod_inventory" type="number" name="inventory" required="required"/> </div>
		<label for="prod_desc"> New Image *</label>
		<div class="edrop-zone">
            <span class="edrop-zone__prompt">Drop file here or click to upload</span>
            <input class="edrop-zone__input" type="file" name="file" required="false" accept="image/jpeg" />
        </div>
        <script src="./admin.js">
            
        </script>
		
        </br>
        <input type="submit" value="Submit" />
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
    </form>
</fieldset>
</br>
<fieldset>
    <legend> Delete Product</legend>
    <form id="prod_delete" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_delete'); ?>" enctype="multipart/form-data">
        <label for="prod_catid"> Product *</label>
        <div> <select id="prod_pid" name="pid"><?php echo $prodOptions; ?></select></div>
        <input type="submit" value="Submit" />
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
    </form>
</fieldset>
</br>
<fieldset>
    <legend> Add Category</legend>
    <form id="cat_insert" method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_insert'); ?>" enctype="multipart/form-data">
        <label for="cat_name"> Name *</label>
        <div> <input id="cat_name" type="text" name="name" required="required" pattern="^[\w\]+$" /></div>
        <input type="submit" value="Submit" />
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
    </form>
</fieldset>
</br>
<fieldset>
    <legend> Edit Category</legend>
    <form id="cat_edit" method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_edit'); ?>" enctype="multipart/form-data">
        <label for="pro_catid"> Original Name *</label>
        <div> <select id="prod_catid" name="catid"><?php echo $catOptions; ?></select></div>
        <label for="prod_name"> New Name *</label>
        <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\]+$" /></div>
        <input type="submit" value="Submit" />
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
    </form>
</fieldset>
</br>
<fieldset>
    <legend> Delete Category</legend>
    <form id="cat_delete" method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_delete'); ?>" enctype="multipart/form-data">
        <label for="cat_delete"> Category *</label>
        <div> <select id="cat_catid" name="catid"><?php echo $catOptions; ?></select></div>
        <input type="submit" value="Submit" />
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
    </form>
</fieldset>
</br>
<fieldset>
    <legend> Add User</legend>
    <form id="add_user" method="POST" action="admin-process.php?action=<?php echo ($action = 'add_user'); ?>" enctype="multipart/form-data">
        <label for="username">Email *</label><br>
		<input type="email" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"><br>
		<label for="pwd">Password *</label><br>
		<input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"><br>
        <label for="admin_flag"> Admin *</label>
        <select id="admin_flag" name="admin_flag" ><?php echo $adminOptions; ?></select>
		<input type="submit" value="Submit" />
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
    </form>
</fieldset>
</body>

</html>

<!-- 
Reference: the code of the drag and drop file uplaod function is refered to the Youtube video 
'Simple Drag and Drop File Upload Tutorial - HTML, CSS & JavaScript' 
URL: https://www.youtube.com/watch?v=Wtrin7C4b7w !-->