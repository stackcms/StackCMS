<?php
/*********************************************************
 * Action:			Delete User Trade Logs
 * Description:		Show page for deleting user trade logs
 */


// Process deletion form
if( isset( $_POST['delete'] ) )
{
	$id = $_POST['id'];
	$delete = $database->query("DELETE FROM `user_trades` WHERE `trd_id`='$id'");

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the trade log was not deleted from the database. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The trade log has been successfully deleted from the database.";
	}
}


// Check if user is accessing a page directly
if( empty( $id ) || empty( $name ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show deletion form
else {
	$get = $database->get_assoc("SELECT * FROM `user_trades` WHERE `trd_id`='$id'");
	echo '<h1>Delete a Trade Log</h1>
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

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&name='.$name.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<p>Are you sure you want to delete this log from '.$get['trd_name'].'? <b>This action can not be undone!</b><br />
	Click on the button below to delete the log:<br />
	<input type="submit" name="delete" class="btn btn-danger" value="Delete this trade log"></p>
	</form>';
}
?>