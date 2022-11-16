<?php
/***************************************************
 * Action:			Edit User Roles
 * Description:		Show page for editing user roles
 */


// Process edit user role form
if( isset( $_POST['update'] ) )
{
	$id = $_POST['id'];
	$title = $sanitize->for_db($_POST['title']);
	$update = $database->query("UPDATE `user_role` SET `role_title`='$title' WHERE `role_id`='$id'");

	if( !$update )
	{
		$error[] = "Sorry, there was an error and the user role was not updated. ".mysqli_error($update);
	}

	else
	{
		$success[] = "The user role was updated successfully!";
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
	$row = $database->get_assoc("SELECT * FROM `user_role` WHERE `role_id`='$id'");
	echo '<h1>Edit a User Role</h1>
	<p>Use this form to edit an existing user role in the database.<br />
	Use the <a href="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action=add">add</a> form to add a new user role.</p>

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
		<div class="col-4"><b>Role Title:</b></div>
		<div class="col"><input type="text" name="title" value="'.$row['role_title'].'" class="form-control" /></div>
	</div><br />

	<input type="submit" name="update" class="btn btn-success" value="Edit Role" /> 
	<input type="reset" name="reset" class="btn btn-danger" value="Reset" /></p>
	</form>
	</div>';
}
?>