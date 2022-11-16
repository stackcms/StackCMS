<?php
/******************************************************
 * Action:			Add Shop Catalog
 * Description:		Show page for adding a shop catalog
 */


// Process addition form
if( isset( $_POST['add-catalog'] ) )
{
	$catalog = $sanitize->for_db($_POST['catalog']);
	$slug = $sanitize->for_db($_POST['slug']);
	$insert = $database->query("INSERT INTO `shop_catalog` (`shop_slug`,`shop_catalog`) VALUES ('$slug','$catalog')");

	// Process form if queries are correct
	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the catalog was not added. ".mysqli_error($insert);
	}

	else
	{
		$database->query("ALTER TABLE `user_items` ADD `itm_$slug` LONGTEXT NOT NULL DEFAULT 'None' AFTER `itm_merchandise`");
		$success[] = "The shop catalog was successfully added to the database!";
	}
}


// Show add catalog form
echo '<h1>Add a Shop Catalog</h1>
<p>Use this form to add a shop catalog/store to the database.<br />
Use the <a href="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'">edit</a> form to update information for existing catalogs/stores.</p>

<center>';
if( isset($error) )
{
	foreach( $error as $msg )
	{
		echo '<div class="alert alert-danger" role="alert"><b>Error!</b> '.$msg.'</div><br />';
	}
}

if( isset($success) )
{
	foreach( $success as $msg )
	{
		echo '<div class="alert alert-success" role="alert"><b>Success!</b> '.$msg.'</div><br />';
	}
}
echo '</center>

<div class="box" style="width: 600px;">
<form method="post" action="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action='.$act.'">
<div class="input-group">
	<div class="input-group-prepend">
		<span class="input-group-text"><b>Catalog Name</b></span>
	</div>
	<input type="text" name="catalog" placeholder="e.g. Card Packs" class="form-control">
</div><br />

<div class="input-group">
	<div class="input-group-prepend">
		<span class="input-group-text"><b>Catalog Slug</b></span>
	</div>
	<input type="text" name="slug" placeholder="e.g. card-packs" class="form-control">
</div><br />

<input type="submit" name="add-catalog" class="btn btn-success" value="Add Catalog" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>