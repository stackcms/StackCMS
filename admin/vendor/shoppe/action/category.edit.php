<?php
/********************************************************
 * Action:			Edit Shop Category
 * Description:		Show page for editing a shop category
 */


// Process edit form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$catalog = $sanitize->for_db($_POST['catalog']);
	$category = $sanitize->for_db($_POST['category']);
	$slug = $sanitize->for_db($_POST['slug']);

	$update = $database->query("UPDATE `shop_category` SET `shop_catalog`='$catalog', `shop_category`='$category', `shop_slug`='$slug' WHERE `shop_id`='$id'");

	if( !$update  )
	{
		$error[] = "Sorry, there was an error and the shop category was not updated. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The shop category has been successfully updated!";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit form
else
{
	$row = $database->get_assoc("SELECT * FROM `shop_category` WHERE `shop_id`='$id'");
	echo '<h1>Edit a Shop Category</h1>
	<p>Use this form to edit a shop category in the database.<br />
	Use the <a href="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action=add">add form</a> to add new categories.</p>

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
	<form method="post" action="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="input-group">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Catalog Name</b></span>
		</div>
		<select name="catalog" class="form-control">';
			$catalog = $database->get_assoc("SELECT * FROM `shop_catalog` WHERE `shop_id`='".$row['shop_catalog']."'");
			echo '<option value="'.$row['shop_catalog'].'">Current: '.$catalog['shop_catalog'].'</option>
			<option value="">----- Select a Catalog -----</option>';
			$catList = $database->query("SELECT * FROM `shop_catalog` ORDER BY `shop_catalog` ASC");
			while( $cat = mysqli_fetch_assoc( $catList ) )
			{
				echo '<option value="'.$cat['shop_id'].'">'.$cat['shop_catalog'].'</option>';
			}
		echo '</select>
	</div><br />

	<div class="input-group">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Category Name</b></span>
		</div>
		<input type="text" name="category" value="'.$row['shop_category'].'" class="form-control">
	</div><br />

	<div class="input-group">
		<div class="input-group-prepend">
			<span class="input-group-text"><b>Category Slug</b></span>
		</div>
		<input type="text" name="slug" value="'.$row['shop_slug'].'" class="form-control">
	</div><br />

	<input type="submit" name="update" class="btn btn-success" value="Edit Catalog" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
	</form>
	</div>';
}
?>