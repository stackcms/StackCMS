<?php
/****************************************************
 * Action:			Edit Shop Item
 * Description:		Show page for editing a shop item
 */


// Process edit an item form
if( isset( $_POST['edit-item'] ) )
{
	$id = $_POST['id'];
	$catalog = $sanitize->for_db($_POST['catalog']);
	$category = $sanitize->for_db($_POST['category']);
	$currency = $sanitize->for_db($_POST['currency']);
	$quantity = $sanitize->for_db($_POST['quantity']);
	$amount = $sanitize->for_db($_POST['amount']);
	$item = $sanitize->for_db($_POST['item']);
	$description = $_POST['entry'];
	$usage = $_POST['usage'];
	$description = nl2br($description);
	$usage = nl2br($usage);

	$description = str_replace("'", "\'", $description);
	$usage = str_replace("'", "\'", $usage);

	if( !empty($_FILES["file"]["name"]) )
	{
		$fileShort = substr_replace($_FILES["file"]["name"],"",-4);
		// $fileName = "C".$catalog."ITM".$category."-".$fileShort;

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

			$database->query("UPDATE `shop_items` SET `shop_catalog`='$catalog',`shop_category`='$category',`shop_file`='$newfilename',`shop_item`='$item',`shop_description`='$description',`shop_usage`='$usage',`shop_currency`='$currency',`shop_quantity`='$quantity',`shop_amount`='$amount' WHERE `shop_id`='$id'") or print("Can't insert into table tcg_shop_items.<br />" . $result . "<br />Error:" . mysqli_connect_error());

			$success[] = "Your shop item has been updated from the database!";
		}

		else
		{
			unlink($filelocation);
			move_uploaded_file($tmp, $filelocation);
			$error[] = "Sorry, there was an error and the shop item was not updated.";
		}
	}

	else
	{
		$update = $database->query("UPDATE `shop_items` SET `shop_catalog`='$catalog',`shop_category`='$category',`shop_item`='$item',`shop_description`='$description',`shop_usage`='$usage',`shop_currency`='$currency',`shop_quantity`='$quantity',`shop_amount`='$amount' WHERE `shop_id`='$id'") or print("Can't insert into table tcg_shop_items.<br />" . $result . "<br />Error:" . mysqli_connect_error());

		if( !$update )
		{
			$error[] = "Sorry, there was an error and the shop item was not updated. ".mysqli_error($update);
		}

		else
		{
			$success[] = "Your shop item has been updated from the database!";
		}
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit an item form
else {
	$row = $database->get_assoc("SELECT * FROM `shop_items` WHERE `shop_id`='$id'");
	echo '<h1>Edit a Shop Item</h1>
	<p>Use this form to edit a shop item in the database.<br />
	Use the <a href="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action=add">add form</a> to add new items.</p>

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
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="row">
		<div class="col">
			<div class="box">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Item Name</b></span>
					</div>
					<input type="text" name="item" value="'.$row['shop_item'].'" class="form-control" />
				</div><br />

				<b>Item Description:</b><br />';
				@include($tcgpath.'admin/theme/text-editor.php');
				echo '<small><i>This content area supports the given HTML tags above, but you can add more such as <code>tables</code> if you need to.</i></small><br />
				<textarea name="entry" id="entry" class="form-control" rows="10" />'.$row['shop_description'].'</textarea><br />

				<b>Item Usage:</b><br />
				<small><i>You can also use HTML tags here, but no PHP!</i></small><br />
				<textarea name="usage" class="form-control" rows="4" />'.$row['shop_usage'].'</textarea>
			</div><!-- box -->
		</div><!-- col -->

		<div class="col-4">
			<div class="box">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Catalog</b></span>
					</div>
					<select name="catalog" class="form-control">';
					$getCatalog = $database->get_assoc("SELECT * FROM `shop_catalog` WHERE `shop_id`='".$row['shop_catalog']."'");
					echo '<option value="'.$row['shop_catalog'].'">Current: '.$getCatalog['shop_catalog'].'</option>
					<option>----- Select a shop catalog -----</option>';
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
					<select name="category" class="form-control">';
					$getCategory = $database->get_assoc("SELECT * FROM `shop_category` WHERE `shop_id`='".$row['shop_category']."'");
					echo '<option value="'.$row['shop_category'].'">Current: '.$getCategory['shop_category'].'</option>';
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
					<input type="text" name="currency" value="'.$row['shop_currency'].'" class="form-control" />
				</div>
				<small><i>For multiple currencies, separate with a vertical slash and a space then a comma.</i></small><br /><br />

				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Amount</b></span>
					</div>
					<input type="text" name="amount" value="'.$row['shop_amount'].'" class="form-control" />
				</div>
				<small><i>If the item is a card pack, define the amount of cards per pack.</i></small><br /><br />

				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Quantity</b></span>
					</div>
					<input type="text" name="quantity" value="'.$row['shop_quantity'].'" class="form-control" />
				</div><br />

				<small><i>Leave this blank to retain the old file.</i></small>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text"><b>Upload File</b></span>
					</div>
					<div class="custom-file">
						<input type="file" name="file" class="custom-file-input" id="inputGroupFile01">
						<label class="custom-file-label" for="inputGroupFile01">Choose file...</label>
					</div>
				</div>

				<input type="submit" name="edit-item" class="btn btn-success" value="Edit Item" /> 
				<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
			</div><!-- box -->
		</div><!-- col-4 -->
	</div><!-- row -->
	</form>';
}
?>