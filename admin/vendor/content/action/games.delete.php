<?php
/************************************************
 * Action:			Delete Games
 * Description:		Show page for deleting a game
 */


// Process deletion of a game form
if ( isset( $_POST['delete'] ) )
{
	$id = $_POST['id'];
	$delete = $database->query("DELETE FROM `tcg_games` WHERE `game_id`='$id'");

	if( !$delete )
	{
		$error[] = "Sorry, there was an error and the game wasn't deleted. ".mysqli_error($delete)."";
	}

	else
	{
		$success[] = "The game was successfully deleted from the database.";
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
	echo '<h1>Delete a Game</h1>
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

	<form method="post" action="'.$tcgurl.'admin/content.php?mod='.$mod.'&action='.$act.'&id='.$id.'">
	<input type="hidden" name="id" value="'.$id.'" />
	<p>Are you sure you want to delete this game? <b>This action can not be undone!</b><br />
	Click on the button below to delete the game:<br />
	<input type="submit" name="delete" class="btn btn-danger" value="Delete this game"></p>
	</form>';
}
?>