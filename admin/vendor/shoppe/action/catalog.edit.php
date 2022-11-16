<?php
/******************************************************
 * Action:			Edit Shop Catalog
 * Description:		Show page for editing shop catalogs
 */


// Process edit form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$catalog = $sanitize->for_db($_POST['catalog']);
	$slug = $sanitize->for_db($_POST['slug']);

	$update = $database->query("UPDATE `shop_catalog` SET `shop_catalog`='$catalog', `shop_slug`='$slug' WHERE `shop_id`='$id'");

	if( !$update  )
	{
		$error[] = "Sorry, there was an error and the shop catalog was not updated. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The shop catalog has been successfully updated!";
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
	$row = $database->get_assoc("SELECT * FROM `shop_catalog` WHERE `shop_id`='$id'");
	echo '<h1>Edit a Shop Catalog</h1>
	<p>Use this form to edit a shop catalog in the database.<br />
	Use the <a href="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action=add">add form</a> to add new catalogs.</p>

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

	<form method="post" action="'.$tcgurl.'admin/shoppe.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<table width="100%" cellpadding="5" cellspacing="0">
	<tr>
		<td width="22%" valign="middle"><b>Catalog:</b>
		<td width="78%"><input type="text" name="catalog" value="'.$row['shop_catalog'].'" size="40" /></td>
	</tr>
	<tr>
		<td valign="middle"><b>Slug:</b>
		<td><input type="text" name="slug" value="'.$row['shop_slug'].'" size="40" /></td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" name="update" class="btn-success" value="Edit Catalog" /> 
			<input type="reset" name="reset" class="btn-cancel" value="Reset" />
		</td>
	</tr>
	</table>';
}
?>