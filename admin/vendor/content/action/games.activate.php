<?php
/**************************************************
 * Action:			Activate Games
 * Description:		Show page for activating a game
 */


// Process activate a game form
if ( isset( $_POST['activate'] ) )
{
	$id = $_POST['id'];
	$activate = $database->query("UPDATE `tcg_games` SET `game_status`='Active' WHERE `game_id`='$id'");

	if( !$activate )
	{
		$error[] = "Sorry, there was an error and the game wasn't activated. ".mysqli_error($activate)."";
	}

	else
	{
		$success[] = "The game was successfully activated from the database.";
	}
}


// Check if user is accessing the page directly
if( empty( $id ) )
{
	echo '<p>This page shouldn\'t be accessed directly! Please go back and try something else.</p>';
}

// Show activation form
else
{
	echo '<h1>Activate a Game</h1>
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
	<p>Are you sure you want to activate this game? Click on the button below to activate the game:<br />
	<input type="submit" name="activate" class="btn btn-success" value="Activate game"></p>
	</form>';
}
?>