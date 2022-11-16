<?php
/*******************************************************
 * Action:			Delete Chat Messages
 * Description:		Show page for deleting chat messages
 */


// Process deletion of a chat message
if( isset( $_POST['delete'] ) )
{
	$id = $_POST['id'];
	$delete = $database->query("DELETE FROM `tcg_chatbox` WHERE `chat_id`='$id'");

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the chat message was not deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The chat message has been deleted from the database!";
	}
}

// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show delete a chat message form
else
{
	$getdata = $database->query("SELECT * FROM `tcg_chatbox` WHERE `chat_id`='$id'");
	echo '<h1>Delete a Chat Message</h1>
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

	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<p>Are you sure you want to delete this chat message? <b>This action can not be undone!</b><br />
	Click on the button below to delete the chat message:<br />
	<input type="submit" name="delete" class="btn btn-danger" value="Delete this entry"></p>
	</form>';
}
?>