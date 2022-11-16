<?php
/****************************************************
 * Action:			Edit User Levels
 * Description:		Show page for editing user levels
 */


// Process edit user levels form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$level = $sanitize->for_db($_POST['level']);
	$name = $sanitize->for_db($_POST['name']);
	$cards = $sanitize->for_db($_POST['cards']);
	$int = $sanitize->for_db($_POST['interval']);

	$update = $database->query("UPDATE `tcg_levels` SET `lvl_name`='$name', `lvl_cards`='$cards', `lvl_interval`='$int' WHERE `lvl_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the level was not updated. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The level was successfully updated.";
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
	$row = $database->get_assoc("SELECT * FROM `tcg_levels` WHERE `lvl_id`='$id'");
	echo '<h1>Edit a User Level</h1>
	<p>Use this form to edit an existing level in the database.<br />
	Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">add</a> form to add a new level.</p>

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
	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<div class="row">
		<div class="col-4"><b>Level Name:</b></div>
		<div class="col"><input type="text" name="name" value="'.$row['lvl_name'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Card Count/Worth:</b></div>
		<div class="col"><input type="text" name="cards" value="'.$row['lvl_cards'].'" class="form-control" /></div>
	</div><br />

	<div class="row">
		<div class="col-4"><b>Card Difference:</b></div>
		<div class="col"><input type="text" name="interval" value="'.$row['lvl_interval'].'" class="form-control" /></div>
	</div><br />

	<input type="submit" name="update" class="btn btn-success" value="Edit Level" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" /></p>
	</form>
	</div>';
}
?>