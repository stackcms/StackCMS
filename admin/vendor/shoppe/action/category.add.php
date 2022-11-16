<?php
/*******************************************************
 * Action:			Add Shop Category
 * Description:		Show page for adding a shop category
 */


// Process addition form
if( isset( $_POST['add-category'] ) )
{
	$catalog = $sanitize->for_db($_POST['catalog']);
	$category = $sanitize->for_db($_POST['category']);
	$slug = $sanitize->for_db($_POST['slug']);

	$insert = $database->query("INSERT INTO `shop_category` (`shop_catalog`,`shop_slug`,`shop_category`) VALUES ('$catalog','$slug','$category')");

	// Process form if queries are correct
	if( !$insert )
	{
		$error[] = "Sorry, there was an error and the category was not added. ".mysqli_error($insert);
	}

	else
	{
		$success[] = "The shop category was successfully added to the database!";
	}
}


// Show addition form
echo '<h1>Add a Shop Category</h1>
<p>Use this form to add a shop category to the database.<br />
Use the <a href="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'">edit</a> form to update information for existing categories.</p>

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

<div class="box" style="width: 600px;">
<form method="post" action="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action='.$act.'">
<div class="input-group">
	<div class="input-group-prepend">
		<span class="input-group-text"><b>Catalog Name</b></span>
	</div>
	<select name="catalog" class="form-control">
		<option value="">----- Select a Catalog -----</option>';
		$catalog = $database->query("SELECT * FROM `shop_catalog` ORDER BY `shop_catalog` ASC");
		while( $cat = mysqli_fetch_assoc( $catalog ) )
		{
			echo '<option value="'.$cat['shop_id'].'">'.$cat['shop_catalog'].'</option>';
		}
	echo '</select>
</div><br />

<div class="input-group">
	<div class="input-group-prepend">
		<span class="input-group-text"><b>Category Name</b></span>
	</div>
	<input type="text" name="category" placeholder="e.g. Choice Cards" class="form-control">
</div><br />

<div class="input-group">
	<div class="input-group-prepend">
		<span class="input-group-text"><b>Category Slug</b></span>
	</div>
	<input type="text" name="slug" placeholder="e.g. choice-cards" class="form-control">
</div><br />

<input type="submit" name="add-category" class="btn btn-success" value="Add Category" /> 
<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
</form>
</div>';
?>