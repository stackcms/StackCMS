<?php
/************************************************************
 * Action:			Delete a Member Deck Task
 * Description:		Show page for deleting a member deck task
 */


// Process deletion form
if( isset( $_POST['delete'] ) )
{
    $id = $_POST['id'];
	$delete = $database->query("DELETE FROM `user_decks` WHERE `task_id`='$id'");

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the freebie was not deleted. ".mysqli_error($delete);
	}

	else
	{
		$success[] = "The freebie has been deleted from the database!";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show deletion form
else
{
    $getdata = $database->query("SELECT * FROM `user_decks` WHERE `task_id`='$id'");
	echo '<h1>Delete a Member Deck Task</h1>
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

	<form method="post" action="'.$tcgurl.'admin/people.php?mod='.$mod.'&page='.$page.'&action='.$act.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<p>Are you sure you want to delete this task? <b>This action can not be undone!</b><br />
	Click on the button below to delete the task:<br />
	<input type="submit" name="delete" class="btn btn-danger" value="Delete this task"></p>
	</form>';
}
?>