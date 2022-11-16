<?php
/****************************************************
 * Action:			Deactivate Games
 * Description:		Show page for deactivating a game
 */


// Process game deactivation form
if ( isset( $_POST['deactivate'] ) )
{
	$id = $_POST['id'];
	$activate = $database->query("UPDATE `tcg_games` SET `game_status`='Inactive' WHERE `game_id`='$id'");

	if( !$activate )
	{
		$error[] = "Sorry, there was an error and the game wasn't deactivated. ".mysqli_error($activate)."";
	}

	else
	{
		$success[] = "The game was successfully deactivated from the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show deactivation form
else
{
	echo '<h1>Deactivate a Game</h1>
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
	<p>Are you sure you want to deactivate this game? Click on the button below to deactivate the game:<br />
	<input type="submit" name="deactivate" class="btn btn-danger" value="Deactivate this game"></p>
	</form>';
}
?>