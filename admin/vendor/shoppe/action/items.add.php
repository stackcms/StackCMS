<?php
/***************************************************
 * Action:			Add Shop Item
 * Description:		Show page for adding a shop item
 */


// Process add shop item form
if( isset( $_POST['add-item'] ) ) {
	$catalog = $sanitize->for_db($_POST['catalog']);
	$category = $sanitize->for_db($_POST['category']);
	$item = $sanitize->for_db($_POST['item']);
	$currency = $sanitize->for_db($_POST['currency']);
	$quantity = $sanitize->for_db($_POST['quantity']);
	$amount = $sanitize->for_db($_POST['amount']);
	$description = $_POST['entry'];
	$usage = $_POST['usage'];
	$description = nl2br($description);
	$usage = nl2br($usage);

	$description = str_replace("'", "\'", $description);
	$usage = str_replace("'", "\'", $usage);

	$fileShort = substr_replace($_FILES["file"]["name"],"",-4);
	//$fileName = "C".$catalog."ITM".$category."-".$fileShort;

	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);

	// this assumes that the upload form calls the form file field "file"
	$name  = $_FILES["file"]["name"];
	$type  = $_FILES["file"]["type"];
	$size  = $_FILES["file"]["size"];
	$tmp   = $_FILES["file"]["tmp_name"];
	$error = $_FILES["file"]["error"];
	$savepath = $tcgpath."shoppe/images/";
	$filelocation = $savepath.$name;
	$newfilename = $savepath.$fileShort;

	if( $size > 300000 )
	{
		echo '<p>Error: Image file must be a maximum of 300KB only.</p>';
	}

	else if( !file_exists($filelocation) && $error == 0 )
	{
		$temp = explode(".", $_FILES["file"]["name"]);
		$newfilename = $fileShort.".".$extension;
		move_uploaded_file($tmp, $path.$savepath.$newfilename);
		$database->query("INSERT INTO `shop_items` (`shop_catalog`,`shop_category`,`shop_file`,`shop_item`,`shop_description`,`shop_usage`,`shop_currency`,`shop_quantity`,`shop_amount`) VALUES ('$catalog','$category','$newfilename','$item','$description','$usage','$currency','$quantity','$amount')") or print("Can't insert into table tcg_shop_items.<br />" . $result . "<br />Error:" . mysqli_connect_error());
		$success[] = "Your shop item has been added into the database!";
	}

	else
	{
		unlink($filelocation);
		move_uploaded_file($tmp, $filelocation);
		$error[] = "Sorry, there was an error and the shop item was not added to the database.";
	}
}


echo '<h1>Add a Shop Item</h1>
<p>Use the form below to add a new shop item for your TCG\'s store.<br />
If you want to update the information for an existing shop item, kindly use the <a href="'.$tcgurl.'admin/shoppe.php">edit form</a> instead.</p>

<center>';
if( isset( $error ) )
{
	foreach( $error as $msg )
	{
		echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
	}
}

if( isset( $success ) )
{
	foreach( $success as $msg )
	{
		echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
	}
}
echo '</center>

<form method="post" action="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action='.$act.'" accept-charset="UTF-8" enctype="multipart/form-data">
<div class="row">
	<div class="col">
		<div class="box">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Item Name</b></span>
				</div>
				<input type="text" name="item" placeholder="e.g. 3 Choice Pack" class="form-control" />
			</div><br />

			<b>Item Description:</b><br />';
			@include($tcgpath.'admin/theme/text-editor.php');
			echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small><br />
			<textarea name="entry" id="entry" class="form-control" rows="10" /></textarea><br />

			<b>Item Usage:</b><br />
			<small><i>You can also use HTML tags here, but no PHP!</i></small><br />
			<textarea name="usage" class="form-control" rows="4" /></textarea>
		</div><!-- box -->
	</div><!-- col -->

	<div class="col-4">
		<div class="box">
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Catalog</b></span>
				</div>
				<select name="catalog" class="form-control">
				<option value="">----- Select a Catalog -----</option>';
				$catalog = $database->query("SELECT * FROM `shop_catalog` ORDER BY `shop_catalog` ASC");
				while( $get = mysqli_fetch_assoc( $catalog ) )
				{
					echo '<option value="'.$get['shop_id'].'">'.$get['shop_catalog'].'</option>';
				}
				echo '</select>
			</div><br />

			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Category</b></span>
				</div>
				<select name="category" class="form-control">
				<option value="">----- Select a Category -----</option>';
				$category = $database->query("SELECT * FROM `shop_category` ORDER BY `shop_category` ASC");
				while( $get = mysqli_fetch_assoc( $category ) )
				{
					echo '<option value="'.$get['shop_id'].'">'.$get['shop_category'].'</option>';
				}
				echo '</select>
			</div><br />

			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Price</b></span>
				</div>
				<input type="text" name="currency" placeholder="e.g. 4 | 2, 5 | 3, 6 | 4" class="form-control" />
			</div>
			<small><i>For multiple currencies, separate with a vertical slash and a space then a comma.</i></small><br /><br />

			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Amount</b></span>
				</div>
				<input type="text" name="amount" placeholder="e.g. 5" class="form-control" />
			</div>
			<small><i>If the item is a card pack, define the amount of cards per pack.</i></small><br /><br />

			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Quantity</b></span>
				</div>
				<input type="text" name="quantity" placeholder="e.g. 50 or Out of Stock" class="form-control" />
			</div><br />

			<small><i>Advisable to have an image for an item even just a placeholder in able to generate an SKU.</i></small>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><b>Upload File</b></span>
				</div>
				<div class="custom-file">
					<input type="file" name="file" class="custom-file-input" id="inputGroupFile01">
					<label class="custom-file-label" for="inputGroupFile01">Choose file...</label>
				</div>
			</div>

			<input type="submit" name="add-item" class="btn btn-success" value="Add Item" /> 
			<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
		</div><!-- box -->
	</div><!-- col-4 -->
</div><!-- row -->
</form>';
?>