<?php
/****************************************************
 * Action:			Delete Event Cards
 * Description:		Show page for deleting event cards
 */


// Process delete an event card form
if ( isset( $_POST['delete'] ) )
{
	$id = $_POST['id'];
	$delete = $database->query("DELETE FROM `tcg_cards_event` WHERE `event_id`='$id'");

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the event card was not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The event card was successfully deleted.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show delete an event card form
else {
	echo '<center>';
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
	<p>Are you sure you want to delete this event card? <b>This action can not be undone!</b><br />
	Click on the button below to delete the event card:<br />
	<input type="submit" name="delete" class="btn btn-danger" value="Delete event card"></p>
	</form>';
}
?>