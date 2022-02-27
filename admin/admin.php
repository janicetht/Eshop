<style>
table{
	border: 1px solid;
	border-collapse: collapse;
}
th, td{
	border: 1px solid;
	border-collapse: collapse;
	padding-left: 5px;
	padding-right: 5px;
	word-wrap: break-word;
}
</style>

<?php
require __DIR__.'/lib/db.inc.php';
$catRes = ierg4210_cat_fetchall();
$prodRes = ierg4210_prod_fetchall();

$catOptions = '';
$prodOptions = '';

foreach ($catRes as $value){
    $catOptions .= '<option value="'.$value['CATID'].'"> '.$value['NAME'].' </option>';
}
foreach ($prodRes as $value){
    $prodOptions .= '<option value="'.$value['PID'].'"> '.$value['NAME'].' </option>';
}
?>

<html>
	<header> <h2> Admin Page </h2> </header>
	
	<fieldset>
	<legend> Category</legend>
	<table>
	<tr>
		<th>Category ID </th>
		<th>Name</th>
	<tr>
	<?php 
	foreach ($catRes as $value){
        print '<tr>';
        print "<td>".$value['CATID']."</td>";
        print "<td>".$value['NAME']."</td>";
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
	<tr>
	<?php 
	foreach ($prodRes as $value){
        print '<tr>';
		print "<td>".$value['PID']."</td>";
        print "<td>".$value['CATID']."</td>";
        print "<td>".$value['NAME']."</td>";
		print "<td>".$value['PRICE']."</td>";
		print "<td>".$value['DESCRIPTION']."</td>";
		print "<td>".$value['COUNTRY']."</td>";
		print "<td>".$value['INVENTORY']."</td>";
        print "</tr>";
	}
	?>
	</tr>
	</table>
	</fieldset>
	</br>
	
    <fieldset>
        <legend> Add Product</legend>
        <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert"
        enctype="multipart/form-data">
            <label for="prod_catid"> Category *</label>
            <div> <select id="prod_catid" name="catid"><?php echo $catOptions; ?></select></div>
            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\]+$"/></div>
            <label for="prod_price"> Price *</label>
            <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$"/></div>
            <label for="prod_desc"> Description *</label>
            <div> <input id="prod_desc" type="text" name="description"/> </div>
			<label for="prod_desc"> Country of Origin *</label>
            <div> <input id="prod_country" type="text" name="country"/> </div>
			<label for="prod_desc"> Inventory *</label>
            <div> <input id="prod_inventory" type="text" name="inventory"/> </div>
            <label for="prod_image"> Image * </label>
            <div> <input type="file" name="file" required="false" accept="image/jpeg"/> </div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
	</br>
	<fieldset>
        <legend> Edit Product</legend>
        <form id="prod_edit" method="POST" action="admin-process.php?action=prod_edit" enctype="multipart/form-data">
            <label for="prod_catid"> Product *</label>
            <div> <select id="pid" name="pid"><?php echo $prodOptions; ?></select></div>
			<label for="prod_catid"> New Category *</label>
            <div> <select id="prod_catid" name="catid"><?php echo $catOptions; ?></select></div>
            <label for="prod_name"> New Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\]+$" /></div>
            <label for="prod_price"> New Price *</label>
            <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$" /></div>
            <label for="prod_desc"> New Description *</label>
            <div> <input id="prod_desc" type="text" name="description" /> </div>
			<label for="prod_desc"> New Country of Origin *</label>
            <div> <input id="prod_country" type="text" name="country"/> </div>
			<label for="prod_desc"> New Inventory *</label>
            <div> <input id="prod_inventory" type="text" name="inventory"/> </div>
            <label for="prod_image"> New Image * </label>
            <div> <input type="file" name="file" required="true" accept="image/jpeg" /> </div>
            <input type="submit" value="Submit" />
        </form>
    </fieldset>
	</br>
    <fieldset>
        <legend> Delete Product</legend>
        <form id="prod_delete" method="POST" action="admin-process.php?action=prod_delete"
            enctype="multipart/form-data">
            <label for="prod_catid"> Product *</label>
            <div> <select id="prod_pid" name="pid"><?php echo $prodOptions; ?></select></div>
            <input type="submit" value="Submit" />
        </form>
    </fieldset>
	</br>
	<fieldset>
        <legend> Add Category</legend>
        <form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert"
        enctype="multipart/form-data">
            <label for="cat_name"> Name *</label>
            <div> <input id="cat_name" type="text" name="name" required="required" pattern="^[\w\]+$"/></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>
	</br>
    <fieldset>
        <legend> Edit Category</legend>
        <form id="cat_edit" method="POST" action="admin-process.php?action=cat_edit" enctype="multipart/form-data">
            <label for="pro_catid"> Original Name *</label>
            <div> <select id="prod_catid" name="catid"><?php echo $catOptions; ?></select></div>
            <label for="prod_name"> New Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\]+$" /></div>
            <input type="submit" value="Submit" />
        </form>
    </fieldset>
	</br>
	<fieldset>
        <legend> Delete Category</legend>
        <form id="cat_delete" method="POST" action="admin-process.php?action=cat_delete" enctype="multipart/form-data">
            <label for="cat_delete"> Category *</label>
            <div> <select id="cat_catid" name="catid"><?php echo $catOptions; ?></select></div>
            <input type="submit" value="Submit" />
        </form>
    </fieldset>
</html>
