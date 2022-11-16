<?php
/***************************************************
 * Action:			Edit Categories
 * Description:		Show page for editing categories
 */


// Process edit categories form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$name = $sanitize->for_db($_POST['name']);
	$update = $database->query("UPDATE `tcg_cards_cat` SET `cat_name`='$name' WHERE `cat_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the category was not updated. ".mysqli_error($update)."";
	}

	else
	{
		$success[] = "The category was successfully updated from the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show edit a category form
else
{
	echo '<h1>Edit a Card Category</h1>
	<p>Use this form to edit an existing category in the database.<br />
	Use the <a href="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action=add">add</a> form to add a new category for your card decks.</p>

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

	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="col" style="width: 600px;">
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text"><b>Category Name:</b></span>
			</div>';
			$row = $database->get_assoc("SELECT * FROM `tcg_cards_cat` WHERE `cat_id`='$id'");
			echo '<input type="text" name="name" value="'.$row['cat_name'].'" class="form-control">
		</div>
	</div>

	<input type="submit" name="update" class="btn btn-success" value="Edit Category" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" />
	</form>';
}
?>