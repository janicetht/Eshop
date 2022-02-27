<style>
    table {
        border: 1px solid;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid;
        border-collapse: collapse;
        padding-left: 5px;
        padding-right: 5px;
        word-wrap: break-word;
    }

    img {
        width: 50px;
    }

	.edrop-zone {
        max-width: 130px;
        height: 130px;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-family: 'Quicksand', sans-serif;
        font-weight: 500;
        font-size 20px;
        cursor: pointer;
        color: grey;
        border: 4px dashed black;
        border-radius: 10px;
    }

    .edrop-zone--over {
        border-style: solid;
    }

    .edrop-zone__input {
        display: none;
    }

    .edrop-zone__thumb {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        overflow: hidden;
        background-color: #cccccc;
        background-size: cover;
        position: relative;
    }

    .edrop-zone__thumb::after {
        content: attr(data-label);
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 5px 0;
        color: white;
        background: rgba(0, 0, 0, 0.75);
        font-size: 14px;
        text-align: center;
    }
</style>

<?php
require __DIR__ . '/lib/db.inc.php';
$catRes = ierg4210_cat_fetchall();
$prodRes = ierg4210_prod_fetchall();

$catOptions = '';
$prodOptions = '';

foreach ($catRes as $value) {
    $catOptions .= '<option value="' . $value['CATID'] . '"> ' . $value['NAME'] . ' </option>';
}
foreach ($prodRes as $value) {
    $prodOptions .= '<option value="' . $value['PID'] . '"> ' . $value['NAME'] . ' </option>';
}
?>

<html>
<header>
    <h2> Admin Page </h2>
</header>

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
    <legend> Add Product</legend>
    <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
        <label for="prod_catid"> Category *</label>
        <div> <select id="prod_catid" name="catid"><?php echo $catOptions; ?></select></div>
        <label for="prod_name"> Name *</label>
        <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\]+$" /></div>
        <label for="prod_price"> Price *</label>
        <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$" /></div>
        <label for="prod_desc"> Description *</label>
        <div> <textarea id="prod_desc" type="text" name="description" rows="4" cols="40"> </textarea> </div>
        <label for="prod_desc"> Country of Origin *</label>
        <div> <input id="prod_country" type="text" name="country" /> </div>
        <label for="prod_desc"> Inventory *</label>
        <div> <input id="prod_inventory" type="text" name="inventory" /> </div>
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
        <div> <textarea id="prod_desc" type="text" name="description" rows="4" cols="40"> </textarea> </div>
        <label for="prod_desc"> New Country of Origin *</label>
        <div> <input id="prod_country" type="text" name="country" /> </div>
        <label for="prod_desc"> New Inventory *</label>
        <div> <input id="prod_inventory" type="text" name="inventory" /> </div>
		<label for="prod_desc"> New Image *</label>
		<div class="edrop-zone">
            <span class="edrop-zone__prompt">Drop file here or click to upload</span>
            <input class="edrop-zone__input" type="file" name="file" required="false" accept="image/jpeg" />
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
    </form>
</fieldset>
</br>
<fieldset>
    <legend> Delete Product</legend>
    <form id="prod_delete" method="POST" action="admin-process.php?action=prod_delete" enctype="multipart/form-data">
        <label for="prod_catid"> Product *</label>
        <div> <select id="prod_pid" name="pid"><?php echo $prodOptions; ?></select></div>
        <input type="submit" value="Submit" />
    </form>
</fieldset>
</br>
<fieldset>
    <legend> Add Category</legend>
    <form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert" enctype="multipart/form-data">
        <label for="cat_name"> Name *</label>
        <div> <input id="cat_name" type="text" name="name" required="required" pattern="^[\w\]+$" /></div>
        <input type="submit" value="Submit" />
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

<!-- 
Reference: the code of the drag and drop file uplaod function is refered to the Youtube video 
'Simple Drag and Drop File Upload Tutorial - HTML, CSS & JavaScript' 
URL: https://www.youtube.com/watch?v=Wtrin7C4b7w --!>