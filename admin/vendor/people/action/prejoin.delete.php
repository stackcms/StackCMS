<?php
/**************************************************************
 * Action:			Delete User Prejoin Record
 * Description:		Show page for deleting user prejoin records
 */


// Process deletion form
if ( isset( $_POST['delete'] ) )
{
	$id = $_POST['id'];
	$delete = $database->query("DELETE FROM `prejoin_record` WHERE `usr_name`='$id'");

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the user prejoin record was not deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The user prejoin record was deleted successfully!";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show deletion form
else {
	echo '<h1>Delete a User Prejoin Record</h1>
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

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<p>Are you sure you want to delete this user prejoin record? <b>This action can not be undone!</b><br />
	Click on the button below to delete the user prejoin record:<br />
	<input type="submit" name="delete" class="btn btn-danger" value="Delete this record"></p>
	</form>';
}
?>